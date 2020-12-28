<?php

use Illuminate\Support\Facades\Route;

//laravel requires the named route of password.reset for generating the email
//url is overwritten to use our spa's domain in AppServiceProvider
Route::get('/reset-password/{token}')
    ->middleware(['guest'])
    ->name('password.reset');


//redirect any login routes to our front end
Route::get('/login', function(\Illuminate\Http\Request $request) {
    return redirect()->to(config('app.spa_url'));
})
    ->middleware(['guest'])
    ->name('login');
