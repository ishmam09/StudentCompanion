<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CGPAController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BillController;

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
    Route::get('/theme/toggle', [DashboardController::class, 'toggleTheme'])->name('theme.toggle');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notes Routes
    Route::get('/notes/create-folder', [\App\Http\Controllers\NoteController::class, 'createFolder'])->name('notes.create-folder');
    Route::get('/notes/upload', [\App\Http\Controllers\NoteController::class, 'upload'])->name('notes.upload');
    Route::get('/notes/{note}/share', [\App\Http\Controllers\NoteController::class, 'share'])->name('notes.share-page');

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
    Route::get('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');

    // Deadline Routes
    Route::resource('deadlines', \App\Http\Controllers\DeadlineController::class);
    Route::patch('/deadlines/{deadline}/toggle', [\App\Http\Controllers\DeadlineController::class, 'toggleStatus'])->name('deadlines.toggle');


    //CGPA-calc Routes
    Route::get('/cgpa', [CGPAController::class, 'index'])->name('cgpa');
    Route::post('/cgpa/calculate', [CGPAController::class, 'calculate'])->name('cgpa.calculate');

    // Tools
    Route::match(['get', 'post'], '/citation-generator', [\App\Http\Controllers\ToolController::class, 'citationGenerator'])->name('citation.index');

    //Expense-Tracker
    Route::get('/expense', [TransactionController::class, 'dashboard'])->name('expense');
    Route::resource('/expense/transactions', TransactionController::class)->names('expense.transactions');
    Route::resource('/expense/categories', CategoryController::class);
    Route::resource('/expense/budgets', BudgetController::class);
    Route::patch('/expense/budgets/{budget}/toggle', [BudgetController::class, 'toggle'])->name('budgets.toggle');
    Route::resource('/expense/bills', BillController::class);
    Route::patch('expense/bills/{bill}/pay', [BillController::class, 'markAsPaid'])->name('bills.pay');
    Route::get('/expense/reports', [TransactionController::class, 'reports'])->name('reports.index');
    Route::get('/expense/reports/export', [TransactionController::class, 'export'])->name('reports.export');

});
