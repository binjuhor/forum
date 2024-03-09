<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Collection;

class PostFixtures
{
    private static Collection $fixture;

    public function getFixture(): Collection
    {
        return self::$fixture ??= $this->withFixture()->make()->mapInto(Post::class);
    }
}
