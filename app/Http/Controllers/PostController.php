<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $posts = Post::query()
            ->when($search, function ($query, $searchTerm) {
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('title', 'like', "%{$searchTerm}%")
                        ->orWhere('content', 'like', "%{$searchTerm}%");
                });
            })
            ->latest()
            ->get();

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $comments = $post->comments()
            ->whereNull('parent_id')
            ->with(['replies' => fn ($query) => $query->latest()])
            ->latest()
            ->get();
        $commentsCount = $post->comments()->count();

        return view('posts.show', [
            'post' => $post,
            'comments' => $comments,
            'commentsCount' => $commentsCount,
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $parameters = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'lead' => ['nullable', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post = new Post;

        $post->title = $parameters['title'];
        $post->slug = $parameters['slug'];
        $post->lead = $parameters['lead'] ?? null;
        $post->author = $parameters['author'];
        $post->content = $parameters['content'];

        // Post::create($parameters);

        $post->save();

        return redirect()->route('posts.index');
    }
}
