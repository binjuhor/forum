<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, Post $post)
    {
        Comment::create([
            ...$request->all(),
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
        ]);

        return to_route('posts.show', $post)->banner('Comment created.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $comment->update($request->validate([
            'body' => ['required', 'string', 'max:2500'],
        ]));

        return to_route('posts.show', [
            'post' => $comment->post_id,
            'page' => $request->query('page'),
        ])->banner('Comment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        $comment->delete();

        return to_route('posts.show', [
            'post' => $comment->post_id,
            'page' => $request->query('page'),
        ])->dangerBanner('Comment deleted.');
    }
}
