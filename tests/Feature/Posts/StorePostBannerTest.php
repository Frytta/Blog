<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores uploaded banner image when creating a post', function () {
    Storage::fake('public');

    $response = $this->post(route('posts.store'), [
        'title' => 'Post z bannerem',
        'slug' => 'post-z-bannerem',
        'lead' => 'Krótka zajawka',
        'author' => 'Jan',
        'content' => 'Treść posta',
        'photo' => UploadedFile::fake()->image('banner.jpg', 1600, 900),
    ]);

    $response->assertRedirect(route('posts.index'));

    $post = Post::query()->where('slug', 'post-z-bannerem')->firstOrFail();

    expect($post->photo)->not->toBeNull();
    Storage::disk('public')->assertExists($post->photo);
});
