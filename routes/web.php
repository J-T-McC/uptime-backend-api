<?php

use Illuminate\Support\Facades\Route;

//laravel requires the named route of password.reset for generating the email
//url is overwritten to use our spa's domain in AppServiceProvider
Route::get('/reset-password/{token}')
    ->middleware(['guest'])
    ->name('password.reset');
