<?php

use App\Models\Comment;

it('generate the html', function () {
    $comment = Comment::factory()->make([
        'body' => '## Hello world',
    ]);

    $comment->save();

    expect($comment->html)->toEqual(str($comment->body)->markdown());
});
