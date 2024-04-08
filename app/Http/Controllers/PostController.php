<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    public function index()
    {
        return inertia('Posts/Index', [
            'posts' => PostResource::collection(Post::with(['user', 'topic'])->latest()->latest('id')->paginate()),
        ]);
    }

    public function create()
    {
        return inertia('Posts/Create');
    }

    public function store(PostRequest $request)
    {
        $data = $request->all();

        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect($post->showRoute())->banner('Post created.');
    }

    public function show(Request $request, Post $post)
    {
        if (! Str::contains($post->showRoute(), $request->path())) {
            return redirect($post->showRoute($request->query()), 301);
        }

        $post->load('user');

        return inertia('Posts/Show', [
            'post' => fn () => PostResource::make($post),
            'comments' => fn () => CommentResource::collection(
                $post->comments()
                    ->latest()
                    ->latest('id')
                    ->with('user')
                    ->paginate(10)
            ),
        ]);
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return new PostResource($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json();
    }
}
