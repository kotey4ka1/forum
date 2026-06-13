<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ForumSection;
use App\Models\SearchQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

    public function index(Request $request)
    {
        $query = $request->input('q');
        if (strlen($query) < 2) {
            return redirect()->route('home');
        }

        // Сохраняем запрос в историю (если включено)
        SearchQuery::create([
            'user_id' => Auth::id(),
            'query' => $query,
            'results_count' => 0,
        ]);

        // Поиск постов
        $posts = Post::with(['user', 'section'])
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['q' => $query]);

        // Поиск разделов
        $sections = ForumSection::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        return view('search.results', compact('query', 'posts', 'sections'));
    }

    // Автодополнение (подсказки)
    public function suggestions(Request $request)
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $postSuggestions = Post::where('title', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn($post) => [
                'type' => 'post',
                'title' => $post->title,
                'link' => route('forum.post', $post->id),
                'snippet' => null,
            ]);

        $sectionSuggestions = ForumSection::where('name', 'like', "%{$q}%")
            ->limit(5)
            ->get()
            ->map(fn($section) => [
                'type' => 'section',
                'title' => $section->name,
                'link' => route('forum.section', $section->id),
                'snippet' => $section->description,
            ]);

        $suggestions = collect($postSuggestions)->merge($sectionSuggestions)->take(10)->values();

        return response()->json(['suggestions' => $suggestions]);
    }
}
