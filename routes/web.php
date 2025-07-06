<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Internal\CompanyController as InternalCompanyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('companies', CompanyController::class)
        ->except(['create', 'show', 'edit'])
        ->middlewareFor(['index'], 'can:read-companies')
        ->middlewareFor(['store'], 'can:create-companies')
        ->middlewareFor(['update'], 'can:update-companies')
        ->middlewareFor(['destroy'], 'can:delete-companies');
    Route::resource('employees', EmployeeController::class)
        ->except(['create', 'show', 'edit'])
        ->middlewareFor(['index'], 'can:read-employees')
        ->middlewareFor(['store'], 'can:create-employees')
        ->middlewareFor(['update'], 'can:update-employees')
        ->middlewareFor(['destroy'], 'can:delete-employees');

    // Profile routes
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // Internal endpoints for UI components
    Route::prefix('internal')->controller(InternalCompanyController::class)->group(function () {
        Route::get('/companies/options', 'options')->name('internal.companies.options');
    });
});

require __DIR__ . '/auth.php';
