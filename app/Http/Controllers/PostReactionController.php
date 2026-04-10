<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostReactionController extends Controller
{
    public function like(Request $request, Post $post): JsonResponse|RedirectResponse
    {
        return $this->react($request, $post, 'like');
    }

    public function dislike(Request $request, Post $post): JsonResponse|RedirectResponse
    {
        return $this->react($request, $post, 'dislike');
    }

    private function react(Request $request, Post $post, string $reaction): JsonResponse|RedirectResponse
    {
        $sessionKey = "post_reactions.{$post->id}";
        $previousReaction = session($sessionKey);

        if ($previousReaction === $reaction) {
            if ($request->expectsJson()) {
                return response()->json([
                    'likes' => $post->likes,
                    'dislikes' => $post->dislikes,
                    'reaction' => $reaction,
                ]);
            }

            return redirect()->back();
        }

        if ($previousReaction === 'like' && $post->likes > 0) {
            $post->decrement('likes');
        }

        if ($previousReaction === 'dislike' && $post->dislikes > 0) {
            $post->decrement('dislikes');
        }

        if ($reaction === 'like') {
            $post->increment('likes');
        }

        if ($reaction === 'dislike') {
            $post->increment('dislikes');
        }

        session([$sessionKey => $reaction]);

        $post->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'likes' => $post->likes,
                'dislikes' => $post->dislikes,
                'reaction' => $reaction,
            ]);
        }

        return redirect()->back();
    }
}
