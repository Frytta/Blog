<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $post = Post::query()->where('slug', $slug)->firstOrFail();

        $parameters = $request->validate([
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('comments', 'id')->where(fn ($query) => $query->where('post_id', $post->id)),
            ],
            'author' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Comment::query()->create([
            'post_id' => $post->id,
            'parent_id' => $parameters['parent_id'] ?? null,
            'author' => $parameters['author'],
            'content' => $parameters['content'],
        ]);

        return redirect()->back()->with('success', 'Komentarz został dodany.');
    }

    public function like(Request $request, Comment $comment): JsonResponse|RedirectResponse
    {
        return $this->react($request, $comment, 'like');
    }

    public function dislike(Request $request, Comment $comment): JsonResponse|RedirectResponse
    {
        return $this->react($request, $comment, 'dislike');
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $parameters = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment->update([
            'content' => $parameters['content'],
        ]);

        return redirect()->back()->with('success', 'Komentarz został zaktualizowany.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Komentarz został usunięty.');
    }

    private function react(Request $request, Comment $comment, string $reaction): JsonResponse|RedirectResponse
    {
        $sessionKey = "comment_reactions.{$comment->id}";
        $previousReaction = session($sessionKey);

        if ($previousReaction === $reaction) {
            return redirect()->back();
        }

        if ($previousReaction === 'like' && $comment->likes > 0) {
            $comment->decrement('likes');
        }

        if ($previousReaction === 'dislike' && $comment->dislikes > 0) {
            $comment->decrement('dislikes');
        }

        if ($reaction === 'like') {
            $comment->increment('likes');
        }

        if ($reaction === 'dislike') {
            $comment->increment('dislikes');
        }

        session([$sessionKey => $reaction]);

        if ($request->expectsJson()) {
            return response()->json([
                'likes' => $comment->likes,
                'dislikes' => $comment->dislikes,
                'reaction' => $reaction,
            ]);
        }

        return redirect()->back();
    }
}
