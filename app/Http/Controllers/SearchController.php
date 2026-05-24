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
        if (empty($query) || strlen($query) < 2) {
            return redirect()->route('home');
        }

        // Сохраняем запрос в историю (если включено)
        SearchQuery::create([
            'user_id' => Auth::id(),
            'query' => $query,
            'results_count' => 0, // временно
        ]);

        // Поиск постов
        $posts = Post::where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(['q' => $query]);

        // Поиск разделов
        $sections = ForumSection::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->get();

        // Обновим количество результатов для последнего запроса
        $totalResults = $posts->total() + $sections->count();
        SearchQuery::where('user_id', Auth::id())->latest()->first()->update(['results_count' => $totalResults]);

        return view('search.results', compact('posts', 'sections', 'query'));
    }

    public function suggestions(Request $request)
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        // Популярные запросы из таблицы search_queries
        $popular = SearchQuery::select('query')
            ->where('query', 'like', $q . '%')
            ->groupBy('query')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->pluck('query');

        // Заголовки постов
        $titles = Post::where('title', 'like', '%' . $q . '%')
            ->limit(10)
            ->pluck('title');

        // Названия разделов
        $sectionNames = ForumSection::where('name', 'like', '%' . $q . '%')
            ->limit(5)
            ->pluck('name');

        $suggestions = $popular->merge($titles)->merge($sectionNames)->unique()->take(10)->values();

        return response()->json($suggestions);
    }
}
