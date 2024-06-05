<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
Use App\Models\post;
use Illuminate\Support\Facades\Auth;
Use App\Models\comment;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $posts = post::get();
    $user = Auth::user();
    return view('dashboard', ['posts' => $posts, 'user' => $user]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/posts/{post}', function (Post $post, Comment $comment) {
    $comments = $comment::get();
    $user = Auth::user();
    return view('post', ['post' => $post, 'comments' => $comments, 'user' => $user]);
})->name('post');

Route::post('/posts/{post}', [RegisteredUserController::class, 'commentAdd'])->name('comments.store');
Route::post('/dashboard', [RegisteredUserController::class, 'postAdd'])->name('posts.store');
Route::delete('/posts/{post}', [RegisteredUserController::class, 'postDelete'])->name('posts.destroy');
Route::delete('/posts/{comment}/delete', [RegisteredUserController::class, 'commentDelete'])->name('comments.destroy');

Route::get('/posts/new', function () {
    return view('postnew');
})->name('postBlog');

require __DIR__.'/auth.php';
