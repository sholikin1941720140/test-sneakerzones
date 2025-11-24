<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login-user', [LoginController::class, 'loginUser']);

Route::middleware('auth')->group(function () { 

    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    Route::get('/staff', [StaffController::class, 'index'])->name('staff-overtime.index');

    Route::get('/spv-pengajuan', [SupervisorController::class, 'index'])->name('overtime.index');
    Route::get('/spv-pengajuan/create', [SupervisorController::class, 'create'])->name('overtime.create');
    Route::post('/spv-pengajuan/store', [SupervisorController::class, 'store'])->name('overtime.store');
    Route::get('/spv-pengajuan/edit/{id}', [SupervisorController::class, 'edit'])->name('overtime.edit');
    Route::post('/spv-pengajuan/update/{id}', [SupervisorController::class, 'update'])->name('overtime.update');
    Route::get('/spv-pengajuan/delete/{id}', [SupervisorController::class, 'delete'])->name('overtime.delete');

    Route::get('/manajer-pengajuan', [ManajerController::class, 'index'])->name('manajer-overtime.index');
    Route::get('/manajer-pengajuan/edit/{id}', [ManajerController::class, 'edit'])->name('manajer-overtime.edit');
    Route::post('/manajer-pengajuan/update/{id}', [ManajerController::class, 'update'])->name('manajer-overtime.update');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin-overtime.index');

    Route::get('/logout', [LoginController::class, 'logout']);
});