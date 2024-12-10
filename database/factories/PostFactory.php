<?php

namespace Database\Factories;

use App\Models\Topic;
use App\Models\User;
use App\Support\PostFixtures;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'topic_id' => Topic::factory(),
            'title' => str(fake()->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn () => fake()->realText(1250))->join(PHP_EOL.PHP_EOL),
            'likes_count' => 0,
        ];
    }

    public function withFixture(): static
    {
        return $this->sequence(...PostFixtures::getFixtures());
    }
}
