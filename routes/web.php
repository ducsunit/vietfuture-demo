<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ActivationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;


// Demo controller routes
Route::get('/demo/quiz', [DemoController::class, 'quiz'])->name('demo.quiz');
Route::post('/demo/progress', [DemoController::class, 'logProgress'])->name('demo.progress');
Route::post('/demo/register-name', [DemoController::class, 'registerName'])->name('demo.registerName');
Route::get('/api/lesson', [DemoController::class, 'getLesson'])->name('api.lesson');
Route::get('/api/points', function() {
    $userId = session('user_id');
    $point = session('point', 0);
    return response()->json(['userId' => $userId, 'point' => (int) $point]);
})->name('api.points');

Route::post('/api/redeem', function(\Illuminate\Http\Request $request) {
    $cost = (int) $request->input('cost', 0);
    $userId = session('user_id');
    if (!$userId) return response()->json(['ok' => false, 'error' => 'Unauthenticated'], 401);
    $user = App\Models\User::find($userId);
    if (!$user) return response()->json(['ok' => false, 'error' => 'User not found'], 404);
    if ((int) $user->point < $cost) return response()->json(['ok' => false, 'error' => 'Not enough points'], 400);
    $user->point = (int) $user->point - $cost;
    $user->save();
    session(['point' => (int) $user->point]);
    return response()->json(['ok' => true, 'point' => (int) $user->point]);
})->name('api.redeem');

Route::post('/api/add-points', function(\Illuminate\Http\Request $request) {
    $inc = (int) $request->input('inc', 0);
    $userId = session('user_id');
    if (!$userId) return response()->json(['ok' => false, 'error' => 'Unauthenticated'], 401);
    $user = App\Models\User::find($userId);
    if (!$user) return response()->json(['ok' => false, 'error' => 'User not found'], 404);
    if ($inc > 0) {
        $user->point = (int) $user->point + $inc;
        $user->save();
        session(['point' => (int) $user->point]);
    }
    return response()->json(['ok' => true, 'point' => (int) $user->point]);
})->name('api.addPoints');

// Activation routes
Route::get('/activate', [ActivationController::class, 'showForm'])->name('activate.form');
Route::post('/activate', [ActivationController::class, 'activate'])->name('activate.submit');

// Parent dashboard
Route::get('/parent', [DemoController::class, 'parentDashboard'])->name('parent.dashboard');

// Community forum
Route::get('/community', [DemoController::class, 'communityIndex'])->name('community.index');
Route::post('/community', [DemoController::class, 'communityCreate'])->name('community.create');
Route::post('/community/{id}/comment', [DemoController::class, 'communityComment'])->name('community.comment');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin CRUD (simple, no middleware here; add later as needed)
Route::prefix('admin')->name('admin.')->group(function () {
    // Books
    Route::get('/books', [AdminController::class, 'booksIndex'])->name('books.index');
    Route::get('/books/create', [AdminController::class, 'booksCreate'])->name('books.create');
    Route::post('/books', [AdminController::class, 'booksStore'])->name('books.store');
    Route::get('/books/{id}/edit', [AdminController::class, 'booksEdit'])->name('books.edit');
    Route::put('/books/{id}', [AdminController::class, 'booksUpdate'])->name('books.update');
    Route::delete('/books/{id}', [AdminController::class, 'booksDestroy'])->name('books.delete');

    // Users
    Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'usersCreate'])->name('users.create');
    Route::post('/users', [AdminController::class, 'usersStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'usersEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'usersUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'usersDestroy'])->name('users.delete');
});
