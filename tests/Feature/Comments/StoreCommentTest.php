<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makePost(array $overrides = []): Post
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

it('stores a root comment for a post', function () {
    $post = makePost();

    $response = $this->post(route('comments.store', $post->slug), [
        'author' => 'Jan Kowalski',
        'content' => 'Bardzo pomocny artykul, dzieki!',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'author' => 'Jan Kowalski',
        'content' => 'Bardzo pomocny artykul, dzieki!',
    ]);
});

it('redirects back after storing comment', function () {
    $post = makePost();

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('comments.store', $post->slug), [
            'author' => 'Piotr',
            'content' => 'To jest odpowiedz',
        ]);

    $response->assertRedirect(route('posts.show', $post->slug));
});

it('validates required comment fields', function () {
    $post = makePost();

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('comments.store', $post->slug), [
            'author' => '',
            'content' => '',
        ]);

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHasErrors(['author', 'content']);
});

it('stores a reply to existing comment', function () {
    $post = makePost();

    $parentComment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Autor glowny',
        'content' => 'Komentarz glowny',
    ]);

    $response = $this->post(route('comments.store', $post->slug), [
        'parent_id' => $parentComment->id,
        'author' => 'Odpowiadajacy',
        'content' => 'To jest odpowiedz do komentarza',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'author' => 'Odpowiadajacy',
        'content' => 'To jest odpowiedz do komentarza',
    ]);
});

it('rejects reply when parent comment belongs to another post', function () {
    $post = makePost();
    $otherPost = makePost();

    $foreignParentComment = Comment::query()->create([
        'post_id' => $otherPost->id,
        'author' => 'Inny autor',
        'content' => 'Inny komentarz',
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->post(route('comments.store', $post->slug), [
            'parent_id' => $foreignParentComment->id,
            'author' => 'Jan',
            'content' => 'Niepoprawna odpowiedz',
        ]);

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHasErrors(['parent_id']);
});

it('updates comment content', function () {
    $post = makePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Anna',
        'content' => 'Stara tresc',
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->patch(route('comments.update', $comment), [
            'content' => 'Nowa tresc komentarza',
        ]);

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHas('success', 'Komentarz został zaktualizowany.');

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'content' => 'Nowa tresc komentarza',
    ]);
});

it('validates required content when updating comment', function () {
    $post = makePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Anna',
        'content' => 'Bez zmian',
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->patch(route('comments.update', $comment), [
            'content' => '',
        ]);

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHasErrors(['content']);

    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
        'content' => 'Bez zmian',
    ]);
});

it('deletes comment', function () {
    $post = makePost();

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Anna',
        'content' => 'Do usuniecia',
    ]);

    $response = $this->from(route('posts.show', $post->slug))
        ->delete(route('comments.destroy', $comment));

    $response->assertRedirect(route('posts.show', $post->slug));
    $response->assertSessionHas('success', 'Komentarz został usunięty.');

    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
});
