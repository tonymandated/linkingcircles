<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title.'-'.fake()->unique()->numberBetween(10, 9999)),
            'excerpt' => fake()->sentence(12),
            'description' => fake()->paragraphs(3, true),
            'status' => 'draft',
            'published_at' => null,
            'created_by' => User::factory(),
        ];
    }

    public function published(): self
    {
        return $this->state(fn (): array => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
