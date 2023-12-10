<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
	public function index()
	{
        return inertia('Posts/Index', [
            'posts' => PostResource::collection(Post::latest()->latest('id')->paginate()),
        ]);
	}

	public function store(PostRequest $request)
	{
		return new PostResource(Post::create($request->validated()));
	}

	public function show(Post $post)
	{
		return new PostResource($post);
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
