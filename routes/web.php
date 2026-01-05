<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CGPAController;
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

    // Quiz Routes
    Route::get('/notes/{note}/quiz/generate', [\App\Http\Controllers\QuizController::class, 'generate'])->name('quiz.generate');
    Route::get('/quiz/{quiz}', [\App\Http\Controllers\QuizController::class, 'show'])->name('quiz.show');
    Route::post('/quiz/{quiz}', [\App\Http\Controllers\QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/result', [\App\Http\Controllers\QuizController::class, 'result'])->name('quiz.result');

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
