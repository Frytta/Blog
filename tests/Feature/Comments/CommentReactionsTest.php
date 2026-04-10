<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createReactablePost(array $overrides = []): Post
{
    return Post::query()->create(array_merge([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(3, true),
        'author' => fake()->name(),
        'is_published' => true,
    ], $overrides));
}

it('increments likes counter for comment', function () {
    $post = createReactablePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Anna',
        'content' => 'Super wpis',
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('comments.like', $comment));

    $response->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'likes' => 1,
        'dislikes' => 0,
    ]);
});

it('increments dislikes counter for comment', function () {
    $post = createReactablePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Marek',
        'content' => 'Nie zgadzam sie',
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('comments.dislike', $comment));

    $response->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'likes' => 0,
        'dislikes' => 1,
    ]);
});

it('increments likes and dislikes for reply comments', function () {
    $post = createReactablePost();

    $parentComment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Parent',
        'content' => 'Komentarz glowny',
    ]);

    $likedReply = Comment::query()->create([
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'author' => 'Reply 1',
        'content' => 'Pierwsza odpowiedz',
    ]);

    $dislikedReply = Comment::query()->create([
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'author' => 'Reply 2',
        'content' => 'Druga odpowiedz',
    ]);

    $this->from(route('posts.show', $post->slug))
        ->post(route('comments.like', $likedReply))
        ->assertRedirect(route('posts.show', $post->slug));

    $this->from(route('posts.show', $post->slug))
        ->post(route('comments.dislike', $dislikedReply))
        ->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('comments', [
        'id' => $likedReply->id,
        'likes' => 1,
        'dislikes' => 0,
    ]);

    $this->assertDatabaseHas('comments', [
        'id' => $dislikedReply->id,
        'likes' => 0,
        'dislikes' => 1,
    ]);
});

it('allows switching reaction for the same comment in one session', function () {
    $post = createReactablePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Ola',
        'content' => 'Moja opinia',
    ]);

    $this->from(route('posts.show', $post->slug))
        ->post(route('comments.like', $comment))
        ->assertRedirect(route('posts.show', $post->slug));

    $switchResponse = $this->from(route('posts.show', $post->slug))
        ->post(route('comments.dislike', $comment));

    $switchResponse->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'likes' => 0,
        'dislikes' => 1,
    ]);
});

it('keeps a single vote when the same reaction is submitted repeatedly', function () {
    $post = createReactablePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Karol',
        'content' => 'Komentarz',
    ]);

    $this->from(route('posts.show', $post->slug))
        ->post(route('comments.like', $comment))
        ->assertRedirect(route('posts.show', $post->slug));

    $this->from(route('posts.show', $post->slug))
        ->post(route('comments.like', $comment))
        ->assertRedirect(route('posts.show', $post->slug));

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'likes' => 1,
        'dislikes' => 0,
    ]);
});

it('redirects back with error when liked comment is missing', function () {
    $post = createReactablePost();

    $response = $this->from(route('posts.show', $post->slug))
        ->post('/comments/999999/like');

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHas('error', 'Komentarz nie istnieje.');
});

it('redirects back with error when disliked comment is missing', function () {
    $post = createReactablePost();

    $response = $this->from(route('posts.show', $post->slug))
        ->post('/comments/999999/dislike');

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHas('error', 'Komentarz nie istnieje.');
});
