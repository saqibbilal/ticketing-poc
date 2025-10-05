<?php
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;

Route::get('/', fn() => redirect('login'));

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::resource('tickets', TicketController::class);
    Route::patch('tickets/{id}/restore', [TicketController::class, 'restore'])->name('tickets.restore');
    Route::patch('tickets/{ticket}/assign-self', [TicketController::class, 'assignToSelf'])->name('tickets.assign-self');

    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
});
