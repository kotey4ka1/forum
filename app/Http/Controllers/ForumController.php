<?php

namespace App\Http\Controllers;

use App\Models\ForumSection;
use App\Models\Post;
use App\Models\SearchQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        // Если есть поисковый запрос, перенаправляем на страницу результатов
        if ($request->filled('search')) {
            return redirect()->route('search.results', ['q' => $request->search]);
        }
        $sections = ForumSection::withCount('posts')->get();
        return view('forum.index', compact('sections'));
    }

    public function section(Request $request, $id)
    {
        $section = ForumSection::findOrFail($id);
        $query = $section->posts()->with('user');

        // Фильтрация и сортировка
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');

        switch ($sort) {
            case 'views':
                $query->orderBy('views_count', $order);
                break;
            case 'likes':
                $query->orderBy('likes_count', $order);
                break;
            case 'comments':
                $query->withCount('comments')->orderBy('comments_count', $order);
                break;
            default:
                $query->orderBy('is_pinned', 'desc')->orderBy('created_at', $order);
                break;
        }

        $posts = $query->paginate(15)->appends($request->query());
        return view('forum.section', compact('section', 'posts'));
    }
}
