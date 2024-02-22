<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => str(fake()->sentence)->beforeLast('.')->title(),
            'body' => Collection::times(4, fn () => fake()->realText(1250))->join(PHP_EOL.PHP_EOL),
        ];
    }

    public function withFixture(): static
    {
        $posts = collect(File::files(database_path('factories/fixtures/posts')))
        ->map(fn(SplFileInfo $file) => $file->getContents())
        ->map(fn (string$contents) => str($contents)->explode("\n", 2))
        ->map( fn (Collection $parts) => [
            'title' => $parts->first(),
            'body' => $parts->last(),
        ]);

        return $this->sequence( ...$posts );
    }
}
