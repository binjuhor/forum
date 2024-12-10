<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use function Pest\Laravel\delete;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    delete(route('likes.destroy', ['post', 1]))->assertRedirect(route('login'));
});

it('allows unliking a likeable', function(Model $likeable) {
    $user = User::factory()->create();
    Like::factory()->for($user)->for($likeable, 'likeable')->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
        ->assertRedirect('dashboard');

    $this->assertDatabaseEmpty(Like::class);

    expect($likeable->refresh()->likes_count)->toBe(0);
})->with([
    fn () => Post::factory()->create(['likes_count' => 1]),
    fn () => Comment::factory()->create(['likes_count' => 1]),
]);

it('prevents unliking something you already liked', function () {
    $likeable = Post::factory()->create();

    actingAs(User::factory()->create())
        ->delete(route('likes.destroy', [$likeable->getMorphClass(), $likeable->id]))
        ->assertForbidden();
});

it('only allows unliking supported models', function () {
   $user = User::factory()->create();

    actingAs($user)
        ->delete(route('likes.destroy', [$user->getMorphClass(), $user->id]))
        ->assertForbidden();
});

it('throws a 404 if the type is unsupported', function () {
   actingAs(User::factory()->create())
         ->delete(route('likes.destroy', ['unsupported', 1]))
         ->assertNotFound();
});
