<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->make([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])->toArray(),
        );

        Tag::query()->upsert([
            ['name' => 'Laravel', 'slug' => 'laravel'],
            ['name' => 'PHP', 'slug' => 'php'],
            ['name' => 'Filament', 'slug' => 'filament'],
            ['name' => 'Livewire', 'slug' => 'livewire'],
            ['name' => 'Tailwind', 'slug' => 'tailwind'],
            ['name' => 'JavaScript', 'slug' => 'javascript'],
            ['name' => 'Docker', 'slug' => 'docker'],
            ['name' => 'DevOps', 'slug' => 'devops'],
            ['name' => 'Testing', 'slug' => 'testing'],
            ['name' => 'Pest', 'slug' => 'pest'],
            ['name' => 'API', 'slug' => 'api'],
            ['name' => 'Performance', 'slug' => 'performance'],
            ['name' => 'Security', 'slug' => 'security'],
            ['name' => 'CI/CD', 'slug' => 'cicd'],
        ], ['slug'], ['name']);
    }
}
