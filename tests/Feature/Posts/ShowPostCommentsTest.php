<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createPost(array $overrides = []): Post
{
    return Post::query()->create(array_merge([
        'title' => fake()->sentence(4),
        'slug' => fake()->unique()->slug(),
        'lead' => fake()->sentence(),
        'content' => fake()->paragraphs(3, true),
        'author' => fake()->name(),
        'is_published' => true,
        'views' => 0,
    ], $overrides));
}

it('increments post views when opening post page', function () {
    $post = createPost(['slug' => 'post-z-licznikiem-views', 'views' => 0]);

    $this->get(route('posts.show', $post->slug))
        ->assertSuccessful();

    expect($post->fresh()->views)->toBe(1);
});

it('shows post views count on post page', function () {
    $post = createPost([
        'slug' => 'post-z-wyswietleniami',
        'views' => 12,
        'likes' => 3131,
        'dislikes' => 1414,
    ]);

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertSuccessful();
    $response->assertSee('Wyświetlenia: 13');
    $response->assertSee('3,131', false);
    $response->assertSee('1,414', false);
});

it('shows root comments and nested replies for a post', function () {
    $post = createPost(['slug' => 'post-z-komentarzami']);
    $otherPost = createPost(['slug' => 'inny-post']);

    $parentComment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Anna',
        'content' => 'Pierwszy komentarz',
    ]);

    Comment::query()->create([
        'post_id' => $post->id,
        'parent_id' => $parentComment->id,
        'author' => 'Bartek',
        'content' => 'Odpowiedz na komentarz',
    ]);

    Comment::query()->create([
        'post_id' => $otherPost->id,
        'author' => 'Ktos inny',
        'content' => 'Nie powinno byc widoczne',
    ]);

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertSuccessful();
    $response->assertSee('Komentarze (2)');
    $response->assertSee('Pierwszy komentarz');
    $response->assertSee('Odpowiedz na komentarz');
    $response->assertDontSee('Nie powinno byc widoczne');
});

it('shows all post comments on post page', function () {
    $post = createPost(['slug' => 'post-paginacja']);

    foreach (range(1, 3) as $index) {
        Comment::query()->create([
            'post_id' => $post->id,
            'author' => "Autor {$index}",
            'content' => "Komentarz {$index}",
        ]);
    }

    $response = $this->get(route('posts.show', $post->slug));

    $response->assertSuccessful();
    $response->assertViewHas('commentsCount', 3);
    $response->assertViewHas('comments', function ($comments): bool {
        return $comments->count() === 3;
    });
});

it('deletes post from post page endpoint', function () {
    $post = createPost(['slug' => 'post-do-usuniecia']);

    $comment = Comment::query()->create([
        'post_id' => $post->id,
        'author' => 'Autor komentarza',
        'content' => 'Komentarz do usuwanego posta',
    ]);

    $response = $this->delete(route('posts.destroy', $post->slug));

    $response->assertRedirect(route('posts.index'));
    $response->assertSessionHas('success', 'Post został usunięty.');

    expect(Post::query()->whereKey($post->id)->exists())->toBeFalse();
    expect(Comment::query()->whereKey($comment->id)->exists())->toBeFalse();
});
