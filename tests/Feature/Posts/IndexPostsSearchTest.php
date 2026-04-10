<?php

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createIndexPost(array $overrides = []): Post
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

it('filters posts by title using search query parameter', function () {
    createIndexPost([
        'title' => 'Laravel wyszukiwarka artykulow',
        'slug' => 'laravel-wyszukiwarka-artykulow',
        'content' => 'Opis implementacji.',
    ]);

    createIndexPost([
        'title' => 'React komponenty',
        'slug' => 'react-komponenty',
        'content' => 'To nie powinno byc widoczne.',
    ]);

    $response = $this->get(route('posts.index', ['search' => 'wyszukiwarka']));

    $response->assertSuccessful();
    $response->assertSee('Laravel wyszukiwarka artykulow');
    $response->assertDontSee('React komponenty');
});

it('filters posts by content using search query parameter', function () {
    createIndexPost([
        'title' => 'Pierwszy wpis',
        'slug' => 'pierwszy-wpis',
        'content' => 'Ten post opisuje testowanie w Pest.',
    ]);

    createIndexPost([
        'title' => 'Drugi wpis',
        'slug' => 'drugi-wpis',
        'content' => 'Artykul o stylowaniu.',
    ]);

    $response = $this->get(route('posts.index', ['search' => 'Pest']));

    $response->assertSuccessful();
    $response->assertSee('Pierwszy wpis');
    $response->assertDontSee('Drugi wpis');
});
