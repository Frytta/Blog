<?php

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores selected existing tags when creating a post', function () {
    $tagA = Tag::query()->create([
        'name' => 'Laravel',
        'slug' => 'laravel',
    ]);

    $tagB = Tag::query()->create([
        'name' => 'Filament',
        'slug' => 'filament',
    ]);

    $payload = [
        'title' => 'Post z tagami',
        'slug' => 'post-z-tagami',
        'lead' => 'Krotki lead',
        'author' => 'Jan Kowalski',
        'content' => 'Pelna tresc posta',
        'tags' => [$tagA->id, $tagB->id],
    ];

    $response = $this->post(route('posts.store'), $payload);

    $response->assertRedirect(route('posts.index'));

    $post = Post::query()->where('slug', 'post-z-tagami')->firstOrFail();

    $this->assertDatabaseHas('post_tag', [
        'post_id' => $post->id,
        'tag_id' => $tagA->id,
    ]);

    $this->assertDatabaseHas('post_tag', [
        'post_id' => $post->id,
        'tag_id' => $tagB->id,
    ]);
});

it('validates that selected tags exist', function () {
    $payload = [
        'title' => 'Post z niepoprawnym tagiem',
        'slug' => 'post-z-niepoprawnym-tagiem',
        'lead' => 'Lead',
        'author' => 'Jan Kowalski',
        'content' => 'Tresc',
        'tags' => [999999],
    ];

    $response = $this->from(route('posts.create'))->post(route('posts.store'), $payload);

    $response->assertRedirect(route('posts.create'));
    $response->assertSessionHasErrors('tags.0');
});

it('shows assigned tags on post page', function () {
    $post = Post::query()->create([
        'title' => 'Post testowy',
        'slug' => 'post-testowy',
        'lead' => 'Lead',
        'content' => 'Tresc',
        'author' => 'Autor',
        'is_published' => true,
    ]);

    $tag = Tag::query()->create([
        'name' => 'PHP',
        'slug' => 'php',
    ]);

    $post->tags()->attach($tag);

    $this->get(route('posts.show', $post->slug))
        ->assertSuccessful()
        ->assertSee('#PHP');
});
