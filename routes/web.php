<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello-world/{name}', [HelloController::class, 'index']);

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{slug}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::post('/comments/{comment}/like', [CommentController::class, 'like'])
    ->name('comments.like')
    ->missing(fn () => redirect()->back()->with('error', 'Komentarz nie istnieje.'));

Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])
    ->name('comments.dislike')
    ->missing(fn () => redirect()->back()->with('error', 'Komentarz nie istnieje.'));
