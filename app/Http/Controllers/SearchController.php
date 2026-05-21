<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
        if ($request->input('save', true)) {
            SearchQuery::create([
                'user_id' => Auth::id(),
                'query' => $query,
                'results_count' => 0, // позже можно обновить
            ]);
        }

        $posts = Post::where('title', 'like', '%' . $query . '%')
            ->orWhere('content', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $posts->appends(['q' => $query]);

        return view('search.results', compact('posts', 'query'));
    }

    public function suggestions(Request $request)
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }
        // Сначала популярные запросы из таблицы search_queries
        $popular = SearchQuery::select('query')
            ->where('query', 'like', $q . '%')
            ->groupBy('query')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(5)
            ->pluck('query');
        // Затем заголовки постов
        $titles = Post::where('title', 'like', '%' . $q . '%')
            ->limit(10)
            ->pluck('title');
        $suggestions = $popular->merge($titles)->unique()->take(10)->values();
        return response()->json($suggestions);
    }
    public function results(Request $request)
    {
        $query = $request->get('q');
        if (empty($query)) {
            return redirect()->route('home');
        }

        // Сохраняем запрос в историю
        SearchQuery::create([
            'user_id' => Auth::id(),
            'query' => $query,
            'results_count' => 0,
        ]);

        $posts = Post::where('title', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->paginate(15)
            ->appends(['q' => $query]);

        // Обновим количество результатов для последнего запроса (по желанию)
        if ($posts->total() > 0) {
            SearchQuery::where('user_id', Auth::id())->latest()->first()->update(['results_count' => $posts->total()]);
        }

        return view('search.results', compact('posts', 'query'));
    }
}
