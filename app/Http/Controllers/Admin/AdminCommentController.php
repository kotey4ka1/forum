<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'commentable']);
        if ($request->filled('search')) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('post_id')) {
            $query->where('commentable_id', $request->post_id)->where('commentable_type', 'App\Models\Post');
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        $comments = $query->orderBy('created_at', 'desc')->paginate(20);
        $posts = Post::all(['id', 'title']);
        $users = User::all(['id', 'name']);
        return view('admin.comments.index', compact('comments', 'posts', 'users'));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Комментарий удалён');
    }
}
