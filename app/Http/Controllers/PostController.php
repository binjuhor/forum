<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\TopicResource;
use App\Models\Post;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Laravel\Scout\Builder as ScoutBuilder;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    public function index(Request $request, ?Topic $topic = null)
    {
        if($request->query('query')) {
            $posts = Post::search($request->query('query'))
                ->query(fn (Builder $query) => $query->with(['user', 'topic']))
                ->when($topic, fn (ScoutBuilder $query) => $query->where('topic_id', $topic->id));
        } else {
            $posts = Post::with(['user', 'topic'])
                ->when($topic, fn (Builder $query) => $query->whereBelongsTo($topic))
                ->latest()
                ->latest('id');
        }

        return inertia('Posts/Index', [
            'posts' => PostResource::collection($posts->paginate()->withQueryString()),
            'topics' => fn () => TopicResource::collection(Topic::all()),
            'selectedTopic' => fn () => $topic ? TopicResource::make($topic) : null,
            'query' => $request->query('query'),
        ]);
    }

    public function create()
    {
        return inertia('Posts/Create', [
            'topics' => fn () => TopicResource::collection(Topic::all()),
        ]);
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
        if (! Str::endsWith($post->showRoute(), $request->path())) {
            return redirect($post->showRoute($request->query()), 301);
        }

        $post->load('user', 'topic');

        return inertia('Posts/Show', [
            'post' => fn () => PostResource::make($post)->withLikePermission(),
            'comments' => function () use ($post) {
                $commentResource = CommentResource::collection($post->comments()->with('user')->latest()->paginate());
                $commentResource->collection->transform(fn ($comment) => $comment->withLikePermission());

                return $commentResource;
            },
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
