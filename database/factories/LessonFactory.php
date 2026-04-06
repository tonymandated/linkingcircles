<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'course_id' => Course::factory(),
            'title' => $title,
            'slug' => Str::slug($title.'-'.fake()->unique()->numberBetween(10, 9999)),
            'summary' => fake()->sentence(10),
            'content' => fake()->paragraphs(4, true),
            'position' => fake()->numberBetween(1, 12),
            'is_published' => true,
            'published_at' => now(),
        ];
    }

    public function unpublished(): self
    {
        return $this->state(fn (): array => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
