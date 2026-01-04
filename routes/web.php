<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\MarketplaceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/check-username', [AuthController::class, 'checkUsername'])->name('check.username');
    
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notes Routes
    Route::get('/notes/{folderId?}', [\App\Http\Controllers\NoteController::class, 'index'])->where('folderId', '[0-9]+')->name('notes.index');
    Route::post('/notes/folder', [\App\Http\Controllers\NoteController::class, 'storeFolder'])->name('notes.store-folder');
    Route::post('/notes/upload', [\App\Http\Controllers\NoteController::class, 'storeNote'])->name('notes.store-note');
    Route::get('/notes/download/{note}', [\App\Http\Controllers\NoteController::class, 'download'])->name('notes.download');
    Route::delete('/notes/folder/{folder}', [\App\Http\Controllers\NoteController::class, 'destroyFolder'])->name('notes.destroy-folder');
    Route::delete('/notes/note/{note}', [\App\Http\Controllers\NoteController::class, 'destroyNote'])->name('notes.destroy-note');

    // Shared Notes Routes
    Route::post('/notes/share/{note}', [\App\Http\Controllers\NoteController::class, 'shareNote'])->name('notes.share');
    Route::get('/notes/shared/inbox', [\App\Http\Controllers\NoteController::class, 'sharedIndex'])->name('notes.shared.index');
    Route::get('/notes/shared/from/{userId}', [\App\Http\Controllers\NoteController::class, 'sharedBy'])->name('notes.shared.by');

    // Notification Routes
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');

    // Deadline Routes
    Route::resource('deadlines', \App\Http\Controllers\DeadlineController::class);
    Route::patch('/deadlines/{deadline}/toggle', [\App\Http\Controllers\DeadlineController::class, 'toggleStatus'])->name('deadlines.toggle');
});

//TO-DO Routes

Route::middleware(['auth'])->group(function () {
    Route::get('/todo-list', [TaskController::class, 'index'])->name('todo.index');
    Route::post('/todo-list', [TaskController::class, 'store'])->name('todo.store');
    Route::delete('/todo-list/{task}', [TaskController::class, 'destroy'])->name('todo.destroy');
});

//Consultation Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::post('/consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('/consultations/{id}/status/{status}', [ConsultationController::class, 'updateStatus'])->name('consultations.status');
});

// Class Routine Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('class-routines', \App\Http\Controllers\ClassRoutineController::class);
    Route::get('/focus-timer', [\App\Http\Controllers\PomodoroController::class, 'index'])->name('pomodoro.index');
    
    // Marketplace
    Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
    Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::post('/marketplace/buy/{id}', [MarketplaceController::class, 'buy'])->name('marketplace.buy');
    Route::get('/marketplace/history', [MarketplaceController::class, 'history'])->name('marketplace.history');
    Route::delete('/marketplace/{id}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
    Route::post('/marketplace/transaction/{id}/undo', [MarketplaceController::class, 'undoPurchase'])->name('marketplace.undo');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/classroom/create', [ClassroomController::class, 'store'])->name('classroom.store');
    Route::post('/classroom/join-code', [ClassroomController::class, 'joinByCode'])->name('classroom.join_code');
    Route::get('/classroom/join/{roomId}', [ClassroomController::class, 'join'])->name('classroom.join');
    Route::get('/classroom/end/{roomId}', [ClassroomController::class, 'end'])->name('classroom.end');
    Route::post('/classroom/leave/{roomId}', [ClassroomController::class, 'leave'])->name('classroom.leave');
    Route::delete('/classroom/{id}', [ClassroomController::class, 'destroy'])->name('classroom.destroy');
    
    // Chat Routes
    Route::get('/classroom/{classId}/messages', [ClassroomController::class, 'getMessages']);
    Route::post('/classroom/message', [ClassroomController::class, 'sendMessage']);

    // Post Routes
    Route::get('/classroom/{classId}/posts', [ClassroomController::class, 'getPosts']);
    Route::post('/classroom/posts', [ClassroomController::class, 'storePost']);
});
