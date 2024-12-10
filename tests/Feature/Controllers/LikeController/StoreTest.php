<?php

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use function Pest\Laravel\post;
use function Pest\Laravel\actingAs;

it('requires authentication', function () {
    post(route('likes.store'))->assertRedirect(route('login'));
});

it('allows liking a likeable', function(Model $likeable) {
    $user = User::factory()->create();

    actingAs($user)
        ->fromRoute('dashboard')
        ->post(route('likes.store', [$likeable->getMorphClass(), $likeable->id]))
        ->assertRedirect('dashboard');

    $this->assertDatabaseHas(Like::class, [
        'user_id' => $user->id,
        'likeable_id' => $likeable->id,
        'likeable_type' => $likeable->getMorphClass(),
    ]);

    expect($likeable->refresh()->likes_count)->toBe(1);
})->with([
    fn () => Post::factory()->create(),
    fn () => Comment::factory()->create(),
]);

it('prevents liking somethign you already liked', function () {
//    $this->withoutExceptionHandling();
    $like = Like::factory()->create();
    $likdable = $like->likeable;

    actingAs($like->user)
        ->post(route('likes.store', [$likdable->getMorphClass(), $likdable->id]))
        ->assertForbidden();
});