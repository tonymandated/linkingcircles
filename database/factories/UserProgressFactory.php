<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LessonFile;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserProgress>
 */
class UserProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $percentComplete = fake()->numberBetween(0, 100);

        return [
            'user_id' => User::factory(),
            'lesson_file_id' => LessonFile::factory(),
            'percent_complete' => $percentComplete,
            'seconds_watched' => fake()->numberBetween(0, 3600),
            'lines_read' => fake()->numberBetween(0, 100),
            'is_completed' => $percentComplete >= 90,
            'completed_at' => $percentComplete >= 90 ? now()->subDays(fake()->numberBetween(1, 30)) : null,
            'last_accessed_at' => now()->subDays(fake()->numberBetween(0, 7)),
        ];
    }

    public function completed(): self
    {
        return $this->state(fn (): array => [
            'percent_complete' => 100,
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function inProgress(): self
    {
        return $this->state(fn (): array => [
            'percent_complete' => fake()->numberBetween(1, 89),
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }
}
