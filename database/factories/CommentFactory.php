<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => Post::query()->create([
                'title' => fake()->sentence(6),
                'slug' => fake()->unique()->slug(),
                'lead' => fake()->sentence(),
                'content' => fake()->paragraphs(3, true),
                'author' => fake()->name(),
                'is_published' => true,
            ])->id,
            'author' => fake()->name(),
            'content' => fake()->paragraph(),
        ];
    }
}
