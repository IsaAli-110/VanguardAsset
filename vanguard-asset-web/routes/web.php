<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\OopShowcaseController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Guest Routes ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [AssetController::class, 'dashboard'])->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // ─── OOP Showcase (semua role bisa akses) ────────────────────────────────
    Route::get('/oop-showcase', [OopShowcaseController::class, 'index'])->name('oop.showcase');

    // ─── Assets ──────────────────────────────────────────────────────────────
    // PENTING: create HARUS sebelum {asset} agar tidak tertimpa
    Route::middleware('role:admin')->group(function () {
        Route::get('/assets/create',        [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets',              [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}/edit',  [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}',       [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{asset}',    [AssetController::class, 'destroy'])->name('assets.destroy');
    });

    // Admin & Manager: export CSV dan PDF
    Route::middleware('role:admin,manager')->group(function () {
        Route::get('/assets/export/csv',    [AssetController::class, 'exportCsv'])->name('assets.export.csv');
        Route::get('/assets/export/pdf',    [AssetController::class, 'exportPdf'])->name('assets.export.pdf');
        Route::get('/assets/maintenance',   [AssetController::class, 'maintenance'])->name('assets.maintenance');
        Route::post('/assets/{asset}/depreciation', [AssetController::class, 'calculateDepreciation'])->name('assets.depreciation');
    });

    // Admin only: mark asset as maintained
    Route::middleware('role:admin')->group(function () {
        Route::post('/assets/{asset}/mark-maintained', [AssetController::class, 'markMaintained'])->name('assets.mark-maintained');
    });

    Route::get('/assets',         [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');

    // ─── Approval Requests ───────────────────────────────────────────────────
    Route::get('/approvals',  [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals', [ApprovalController::class, 'store'])->name('approvals.store');

    Route::middleware('role:admin,manager')->group(function () {
        Route::patch('/approvals/{approvalRequest}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::patch('/approvals/{approvalRequest}/reject',  [ApprovalController::class, 'reject'])->name('approvals.reject');
    });

    Route::patch('/assets/{asset}/return', [ApprovalController::class, 'returnAsset'])->name('approvals.return');

    // ─── Admin Only: User Management & Security ──────────────────────────────
    Route::middleware('role:admin')->group(function () {
        // User Management
        Route::get('/users',                [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role',  [UserManagementController::class, 'updateRole'])->name('users.updateRole');

        // Security Center
        Route::get('/security/dashboard',   [SecurityController::class, 'dashboard'])->name('security.dashboard');
        Route::get('/security/logs',        [SecurityController::class, 'logs'])->name('security.logs');
        Route::get('/security/sessions',    [SecurityController::class, 'sessions'])->name('security.sessions');
        Route::delete('/security/sessions/{id}', [SecurityController::class, 'destroySession'])->name('security.sessions.destroy');
    });
});
