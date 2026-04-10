<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostReactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello-world/{name}', [HelloController::class, 'index']);

Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
Route::delete('/posts/{slug}', [PostController::class, 'destroy'])
    ->name('posts.destroy')
    ->missing(fn () => redirect()->route('posts.index')->with('error', 'Post nie istnieje.'));
Route::post('/posts/{slug}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/posts/{post:slug}/like', [PostReactionController::class, 'like'])
    ->name('posts.like');
Route::post('/posts/{post:slug}/dislike', [PostReactionController::class, 'dislike'])
    ->name('posts.dislike');

Route::post('/comments/{comment}/like', [CommentController::class, 'like'])
    ->name('comments.like')
    ->missing(fn () => redirect()->back()->with('error', 'Komentarz nie istnieje.'));

Route::post('/comments/{comment}/dislike', [CommentController::class, 'dislike'])
    ->name('comments.dislike')
    ->missing(fn () => redirect()->back()->with('error', 'Komentarz nie istnieje.'));

Route::patch('/comments/{comment}', [CommentController::class, 'update'])
    ->name('comments.update')
    ->missing(fn () => redirect()->back()->with('error', 'Komentarz nie istnieje.'));

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy')
    ->missing(fn () => redirect()->back()->with('error', 'Komentarz nie istnieje.'));
