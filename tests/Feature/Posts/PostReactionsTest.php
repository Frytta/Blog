<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows ratings arrows next to views on index page', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
        'views' => 12345,
        'likes' => 7771,
        'dislikes' => 2229,
    ]);

    $response = $this->get(route('posts.index'));

    $response->assertSuccessful();
    $response->assertSee('👁 12,345', false);
    $response->assertSee('7,771', false);
    $response->assertSee('2,229', false);
});

it('increments likes counter for post', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('posts.like', $post));

    $response->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'likes' => 1,
        'dislikes' => 0,
    ]);
});

it('increments dislikes counter for post', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('posts.dislike', $post));

    $response->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'likes' => 0,
        'dislikes' => 1,
    ]);
});

it('allows switching reaction for the same post in one session', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
    ]);

    $this->from(route('posts.show', $post->slug))
        ->post(route('posts.like', $post))
        ->assertRedirect(route('posts.show', $post->slug));

    $this->from(route('posts.show', $post->slug))
        ->post(route('posts.dislike', $post))
        ->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'likes' => 0,
        'dislikes' => 1,
    ]);
});

it('returns json payload for post reaction ajax request', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
    ]);

    $response = $this->postJson(route('posts.like', $post));

    $response
        ->assertSuccessful()
        ->assertJson([
            'likes' => 1,
            'dislikes' => 0,
            'reaction' => 'like',
        ]);
});

it('shows ratings arrows next to views on post page', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
        'views' => 9,
        'likes' => 4443,
        'dislikes' => 1114,
    ]);

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertSuccessful();
    $response->assertSee('Wyświetlenia: 10');
    $response->assertSee('4,443', false);
    $response->assertSee('1,114', false);
});

it('does not render post reaction buttons section', function () {
    $post = Post::query()->create([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(2, true),
        'author' => fake()->name(),
        'is_published' => true,
    ]);

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertSuccessful();
    $response->assertDontSee('Reakcje na post');
    $response->assertSee('Wyświetlenia:');
});
