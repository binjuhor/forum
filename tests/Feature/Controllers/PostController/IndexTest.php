<?php

use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\get;

it('should return the correct component', function () {
    get(route('posts.index'))
        ->assertInertia(fn (AssertableInertia $inertia) => $inertia
            ->component('Posts/Index', true)
        );
});

it('passes posts to the view', function () {
    $posts = Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index'))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('it can filter a topic', function () {
    $general = Topic::factory()->create();

    $posts = Post::factory(2)->for($general)->create();
    Post::factory(3)->create();

    $posts->load(['user', 'topic']);

    get(route('posts.index', ['topic' => $general]))
        ->assertHasPaginatedResource('posts', PostResource::collection($posts->reverse()));
});

it('passed the selected topic to the view', function () {
    $topic = Topic::factory()->create();

    get(route('posts.index', ['topic' => $topic]))
        ->assertHasResource('selectedTopic', TopicResource::make($topic));
});


