<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $posts = Post::query()
            ->with('tags')
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
        $post = Post::query()->where('slug', $slug)->with('tags')->firstOrFail();
        $post->increment('views');
        $post->refresh();
        $post->load('tags');

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
        return view('posts.create', [
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $parameters = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug'],
            'lead' => ['nullable', 'string'],
            'author' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post = new Post;

        $post->title = $parameters['title'];
        $post->slug = $parameters['slug'];
        $post->lead = $parameters['lead'] ?? null;
        $post->author = $parameters['author'];
        $post->content = $parameters['content'];

        if ($request->hasFile('photo')) {
            $post->photo = $request->file('photo')->store('posts', 'public');
        }

        // Post::create($parameters);

        $post->save();
        $post->tags()->sync($parameters['tags'] ?? []);

        return redirect()->route('posts.index');
    }

    public function destroy(string $slug)
    {
        $post = Post::query()->where('slug', $slug)->firstOrFail();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post został usunięty.');
    }
}
