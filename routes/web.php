<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AuthController;
use App\Http\Controllers\admin\DatabaseExportImportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () { return view('welcome');});

Auth::routes([
    'reset' => false, 
    'register' => false, 
    'login' => false 
]);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/mfa', [AuthController::class, 'showMFAForm'])->name('mfa.form');
Route::post('/mfa/verify', [AuthController::class, 'verifyMFA'])->name('mfa.verify.submit');
Route::post('/mfa/resend', [AuthController::class, 'resendMFA'])->name('mfa.resend');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('export-import', [DatabaseExportImportController::class, 'showExportImportPage'])->name('admin.export-import');
    Route::post('export', [DatabaseExportImportController::class, 'exportData'])->name('admin.export');
    Route::post('import', [DatabaseExportImportController::class, 'importData'])->name('admin.import');
    Route::post('/admin/insert-data', [DatabaseExportImportController::class, 'insertData'])->name('admin.insert-data');
    Route::post('/admin/remove-all-data', [DatabaseExportImportController::class, 'removeAllData'])->name('admin.removeAllData');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
