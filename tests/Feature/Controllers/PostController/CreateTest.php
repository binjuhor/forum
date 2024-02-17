<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('requires authentication', function () {
    get(route('posts.create'))->assertRedirect(route('login'));
});

it('store a post', function () {
    actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertComponent('Posts/Create');
});
