<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AuditManagementController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Customer\AuditController;
use App\Http\Controllers\Customer\CustomerAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Guest Routes (Accessible only if NOT logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Accessible only if logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Customer CRUD Management
    Route::resource('customers', CustomerController::class);

    // Fixed Admin Audit Management Routes
    Route::prefix('audit')->name('audit.')->group(function () {
        // Main view dashboard mapping
        Route::get('/', [AuditManagementController::class, 'index'])->name('index');
        
        // Category creation mapping
        Route::post('/category', [AuditManagementController::class, 'storeCategory'])->name('category.store');
        
        // Question management mappings matching layout template expectations
        Route::get('/question/create', [AuditManagementController::class, 'createQuestion'])->name('question.create');
        Route::post('/question', [AuditManagementController::class, 'storeQuestion'])->name('question.store');
        Route::get('/question/{id}/edit', [AuditManagementController::class, 'editQuestion'])->name('question.edit');
        Route::put('/question/{id}', [AuditManagementController::class, 'updateQuestion'])->name('question.update');
        Route::delete('/question/{id}', [AuditManagementController::class, 'destroyQuestion'])->name('question.destroy');
        
        // Customer Audit Reports (Admin View)
        Route::get('/customer-audits', [AuditManagementController::class, 'customerAudits'])->name('customer_audits');
        Route::get('/customer-audits/{id}/report', [AuditManagementController::class, 'viewCustomerReport'])->name('customer_report');
        Route::get('/customer-audits/{id}/pdf', [AuditManagementController::class, 'downloadCustomerPdf'])->name('customer_pdf');
    });

    // Logout Route
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Customer Guest Routes (Accessible only if NOT logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:customer')->prefix('customer')->name('customer.')->group(function () {
    // Login
    Route::get('/login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
    
    // Registration
    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
    
    // OTP Handling
    Route::get('/verify', [CustomerAuthController::class, 'showVerifyPage'])->name('verify.page');
    Route::post('/verify', [CustomerAuthController::class, 'verifyOtp'])->name('verify.submit');
});

/*
|--------------------------------------------------------------------------
| Customer Protected Routes (Only verified & logged-in customers)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:customer')->prefix('customer')->name('customer.')->group(function () {
    
    // Alias to fix the RouteNotFoundException for customer.dashboard
    Route::get('/dashboard', [AuditController::class, 'dashboard'])->name('dashboard');

    // Group everything related to the audit engine with the 'audit.' name prefix
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/dashboard', [AuditController::class, 'dashboard'])->name('dashboard');
        Route::post('/start', [AuditController::class, 'startAudit'])->name('start');
        
        // Active step layouts & draft storage triggers
        Route::get('/session/{audit}/category/{category}', [AuditController::class, 'showStep'])->name('step');
        Route::post('/session/{audit}/category/{category}/save', [AuditController::class, 'saveStep'])->name('step.save');
        
        // Reports and dynamic downloads structures
        Route::get('/session/{audit}/report', [AuditController::class, 'showReport'])->name('report');
        Route::get('/session/{audit}/pdf-stream-download', [AuditController::class, 'downloadPdf'])->name('pdf');
    });

    // Global customer actions
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
});