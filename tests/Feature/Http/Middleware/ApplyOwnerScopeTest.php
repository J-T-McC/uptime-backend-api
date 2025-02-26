<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\ApplyOwnerScope;
use App\Models\Channel;
use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\MonitorUptimeEventCount;
use App\Models\Scopes\OwnerScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

#[CoversClass(ApplyOwnerScope::class)]
class ApplyOwnerScopeTest extends TestCase
{
    /**
     * @see ApplyOwnerScope::handle
     */
    public function test_it_is_assigned_to_expected_routes()
    {
        // Collect
        $routes = Route::getRoutes()->getRoutes();

        $routes = array_filter($routes, function ($route) {
            return str_starts_with($route->uri, 'api') && $route->getName() !== 'api.home';
        });

        $this->assertNotEmpty($routes);

        foreach ($routes as $route) {
            $middleware = $route->gatherMiddleware();

            // Assert
            $this->assertContains(ApplyOwnerScope::class, $middleware);
        }
    }

    /**
     * @see ApplyOwnerScope::handle
     */
    public function test_it_applies_owner_scope_to_expected_models(): void
    {
        // Collect
        $middleware = new ApplyOwnerScope;
        $requestMock = $this->mock(Request::class);
        $responseMock = $this->mock(Response::class);

        // Assert
        $this->assertFalse(Monitor::hasGlobalScope(OwnerScope::class));
        $this->assertFalse(Channel::hasGlobalScope(OwnerScope::class));
        $this->assertFalse(MonitorEvent::hasGlobalScope(OwnerScope::class));
        $this->assertFalse(MonitorUptimeEventCount::hasGlobalScope(OwnerScope::class));

        // Act
        $middleware->handle($requestMock, fn () => $responseMock);

        // Assert
        $this->assertTrue(Monitor::hasGlobalScope(OwnerScope::class));
        $this->assertTrue(Channel::hasGlobalScope(OwnerScope::class));
        $this->assertTrue(MonitorEvent::hasGlobalScope(OwnerScope::class));
        $this->assertTrue(MonitorUptimeEventCount::hasGlobalScope(OwnerScope::class));
    }
}
