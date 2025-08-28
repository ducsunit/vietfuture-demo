<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// Redirect root to login with default book/lesson (bypass welcome page)
Route::get('/', function () {
    return redirect()->route('login', [
        'book' => 'phong-chong-duoi-nuoc', 
        'lesson' => 'an-toan-nuoc'
    ]);
})->name('welcome');


// Demo controller routes
Route::get('/demo/quiz', [DemoController::class, 'quiz'])->name('demo.quiz');
Route::get('/quiz', [DemoController::class, 'quiz'])->name('quiz'); // Alias for easier access
Route::post('/demo/progress', [DemoController::class, 'logProgress'])->name('demo.progress');

Route::get('/api/lesson', [DemoController::class, 'getLesson'])->name('api.lesson');
Route::get('/api/points', function() {
    $userId = session('user_id');
    $username = session('username', '');
    $point = session('point', 0);
    
    // Sync điểm từ database để đảm bảo chính xác
    if ($userId) {
        $user = App\Models\User::find($userId);
        if ($user) {
            $point = (int) $user->point;
            session(['point' => $point]); // update session
        }
    }
    
    return response()->json([
        'userId' => $userId, 
        'username' => $username,
        'point' => (int) $point
    ]);
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

// Reward System Routes
Route::get('/api/rewards', [App\Http\Controllers\RewardController::class, 'index'])->name('api.rewards.index');
Route::get('/api/rewards/user', [App\Http\Controllers\RewardController::class, 'getUserRewards'])->name('api.rewards.user');
Route::post('/api/rewards/purchase', [App\Http\Controllers\RewardController::class, 'purchase'])->name('api.rewards.purchase');
Route::post('/api/rewards/equip', [App\Http\Controllers\RewardController::class, 'equip'])->name('api.rewards.equip');
Route::get('/api/rewards/background', [App\Http\Controllers\RewardController::class, 'getEquippedBackground'])->name('api.rewards.background');

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

Route::post('/api/set-display-name', function(\Illuminate\Http\Request $request) {
    $userId = session('user_id');
    if (!$userId) return response()->json(['ok' => false, 'error' => 'Unauthenticated'], 401);
    
    $data = $request->validate([
        'name' => 'required|string|min:2|max:100',
    ]);
    
    $user = App\Models\User::find($userId);
    if (!$user) return response()->json(['ok' => false, 'error' => 'User not found'], 404);
    
    $user->display_name = $data['name'];
    $user->save();
    
    return response()->json(['ok' => true, 'display_name' => $user->display_name]);
})->name('api.setDisplayName');

Route::get('/api/get-display-name', function() {
    $userId = session('user_id');
    if (!$userId) return response()->json(['ok' => false, 'error' => 'Unauthenticated'], 401);
    
    $user = App\Models\User::find($userId);
    if (!$user) return response()->json(['ok' => false, 'error' => 'User not found'], 404);
    
    return response()->json(['ok' => true, 'display_name' => $user->display_name]);
})->name('api.getDisplayName');



// Parent dashboard
Route::get('/parent', [DemoController::class, 'parentDashboard'])->name('parent');

// Community forum
Route::get('/community', [DemoController::class, 'communityIndex'])->name('community');
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
