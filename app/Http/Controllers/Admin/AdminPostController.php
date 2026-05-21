<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ForumSection;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'section']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('section_id')) {
            $query->where('forum_section_id', $request->section_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('status')) {
            if ($request->status == 'pinned') $query->where('is_pinned', 1);
            elseif ($request->status == 'unpinned') $query->where('is_pinned', 0);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(20);
        $sections = ForumSection::all();
        $users = User::all();

        return view('admin.posts.index', compact('posts', 'sections', 'users'));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Пост удалён');
    }

    public function togglePin(Post $post)
    {
        $post->is_pinned = !$post->is_pinned;
        $post->save();
        return back()->with('success', 'Статус закрепления изменён');
    }

    public function moveForm(Post $post)
    {
        $sections = ForumSection::all();
        return view('admin.posts.move', compact('post', 'sections'));
    }

    public function move(Request $request, Post $post)
    {
        $request->validate(['section_id' => 'required|exists:forum_sections,id']);
        $post->forum_section_id = $request->section_id;
        $post->save();
        return redirect()->route('admin.posts.index')->with('success', 'Пост перемещён');
    }
}
