import http from 'k6/http';
import { check, sleep } from 'k6';
import { Trend, Rate } from 'k6/metrics';

// Create custom metrics
let loginDuration = new Trend('login_duration'); // Tracks the login request duration
let loginFailureRate = new Rate('login_failures'); // Tracks the failure rate of login requests
let csrfRequestDuration = new Trend('csrf_request_duration'); // Tracks CSRF request duration
let csrfFailureRate = new Rate('csrf_failures'); // Tracks the failure rate of CSRF token retrieval
let indexDuration = new Trend('index_duration'); // Tracks the duration of the index request
let indexFailureRate = new Rate('index_failures'); // Tracks the failure rate of index requests

// Step 1: Retrieve CSRF token and store cookies
export function setup() {
    const jar = http.cookieJar();
    const url = 'https://octane-uptime-api.tysonmccarney.com/sanctum/csrf-cookie';
    const loginUrl = 'https://octane-uptime-api.tysonmccarney.com/login';
    const loginPayload = JSON.stringify({
        email: '...',
        password: '...',
    });

    const csrfParams = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Origin': 'uptime-test.tysonmccarney.com',
            'Referer': 'uptime-test.tysonmccarney.com/',
        },
        cookies: jar,
    };

    // Step 1: Retrieve CSRF token
    let csrfRequestStart = new Date().getTime(); // Start time for CSRF request
    const csrfRes = http.get(url, csrfParams);
    let csrfRequestDurationValue = new Date().getTime() - csrfRequestStart;
    csrfRequestDuration.add(csrfRequestDurationValue); // Add CSRF request duration to custom trend

    // Track CSRF failures (e.g., missing CSRF token)
    const csrfSuccess = csrfRes.status === 204;
    check(csrfRes, {
        'CSRF request successful': csrfSuccess,
    });
    csrfFailureRate.add(!csrfSuccess);

    if (!csrfSuccess) {
        console.error('CSRF request failed!');
        return;
    }

    // Step 2: Retrieve CSRF token from cookies
    const cookies = jar.cookiesForURL(loginUrl);
    const xsrfToken = cookies && cookies['XSRF-TOKEN'] ? decodeURIComponent(cookies['XSRF-TOKEN'][0]) : null;

    if (!xsrfToken) {
        console.error('CSRF Token not found!');
        return;
    }

    const loginParams = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Origin': 'uptime-test.tysonmccarney.com',
            'Referer': 'uptime-test.tysonmccarney.com/',
            'x-xsrf-token': xsrfToken, // Send the CSRF token in the headers
        },
        cookies: jar,
    };

    // Step 3: Authenticate with the server
    let loginRequestStart = new Date().getTime(); // Start time for login request
    const loginRes = http.post(loginUrl, loginPayload, loginParams);
    let loginRequestDuration = new Date().getTime() - loginRequestStart;
    loginDuration.add(loginRequestDuration); // Add login duration to custom trend

    // Step 4: Check the response for successful login
    const loginSuccess = loginRes.status === 200;
    check(loginRes, {
        'Login successful': loginSuccess,
    });

    // Track login failures (e.g., CSRF token mismatch)
    loginFailureRate.add(!loginSuccess);

    if (!loginSuccess) {
        console.error('Login failed!');
        return;
    }

    const cookies2 = jar.cookiesForURL(loginUrl);

    const decodedXSRFToken = decodeURIComponent(cookies2['XSRF-TOKEN'][0]);
    const decodedUptimeSession = decodeURIComponent(cookies2['uptime_session'][0]);

    // Step 5: Override the cookies in the cookie jar with decoded values
    jar.set(loginUrl, 'XSRF-TOKEN', decodedXSRFToken);
    jar.set(loginUrl, 'uptime_session', decodedUptimeSession);

    // Store the cookie jar in the global state for use in VUs
    return jar.cookiesForURL(loginUrl);  // Store the cookie jar for reuse in the default function
}

// Step 2: Load test the index endpoint
export default function (cookies) {
    // Step 2: Create a new cookie jar
    const cookiesJar = http.cookieJar();
    // const indexUrl = 'https://octane-uptime-api.tysonmccarney.com/api/monitors?include=channels&sort=uptime_status,-id';
    const indexUrl = 'https://octane-uptime-api.tysonmccarney.com/api/authenticated';

    // Step 3: Set the cookies into the cookie jar
    cookies["XSRF-TOKEN"].forEach((token, index) => {
        cookiesJar.set(indexUrl, 'XSRF-TOKEN', token);
    });

    cookies["uptime_session"].forEach((session, index) => {
        cookiesJar.set(indexUrl, 'uptime_session', session);
    });

    const indexParams = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Origin': 'uptime-test.tysonmccarney.com',
            'Referer': 'uptime-test.tysonmccarney.com/',
        },
        cookies: cookiesJar, // Use the stored cookie jar with authentication cookies
    };


    let indexRequestStart = new Date().getTime(); // Start time for index request
    const indexRes = http.get(indexUrl, indexParams);
    let indexDurationValue = new Date().getTime() - indexRequestStart;
    indexDuration.add(indexDurationValue); // Add index request duration to custom trend

    // Step 6: Check for successful API request (e.g., status 200)
    const indexSuccess = indexRes.status === 200;
    check(indexRes, {
        'Index request successful': indexSuccess,
    });

    // Track index failures
    indexFailureRate.add(!indexSuccess);
}

export let options = {
    vus: 20,  // Virtual users
    duration: '30s',  // Load test duration
    thresholds: {
        'login_failures': ['rate<0.01'], // Allow less than 1% failures in login requests
        'login_duration': ['p(95)<2000'], // 95% of login requests should take less than 2 seconds
        'csrf_request_duration': ['p(95)<1000'], // 95% of CSRF requests should take less than 1 second
        'index_duration': ['p(95)<1000'], // 95% of Index requests should take less than 1 second
        'csrf_failures': ['rate<0.01'], // Allow less than 1% failures in CSRF requests
        'index_failures': ['rate<0.01'], // Allow less than 1% failures in Index requests
    },
};
