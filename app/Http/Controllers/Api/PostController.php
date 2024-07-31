<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = auth()->user()->id;
        $posts = Post::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($posts, 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);


        $post = new Post();
        $post->user_id = auth()->user()->id;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->view_count = 0;
        $post->save();

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted'], 200);
    }
    public function associatedPosts()
    {
        $perPage = 10;
        $posts = Post::withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $posts->items(),
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage()
        ], 200);
    }

    public function like(Post $post)
    {
        $user = Auth::user();
        // Check if the user already liked the post
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already liked'], 400);
        }
        $post->likes()->create(['user_id' => $user->id]);
        return response()->json(['success' => true]);
    }

    public function unlike(Post $post)
    {
        $user = Auth::user();
        $like = $post->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['message' => 'Not liked yet'], 400);
    }

    public function storeComment(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->user_id = auth()->user()->id;
        $comment->content = $request->content;
        $comment->save();

        return response()->json(['message' => 'Comment added successfully'], 201);
    }

    public function getComments($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $comments = $post->comments()->with('user')->get();

        return response()->json($comments, 200);
    }
}
