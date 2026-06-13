<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    // Публичный список статей для пользователей
    public function index(Request $request)
    {
        $query = KnowledgeBase::where('is_published', true);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('question', 'like', '%' . $request->search . '%')
                    ->orWhere('answer', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->orderBy('category')->orderBy('created_at', 'desc')->paginate(10);
        $categories = KnowledgeBase::select('category')->distinct()->pluck('category');

        return view('faq.index', compact('articles', 'categories'));
    }

    // Публичный просмотр одной статьи
    public function show(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->increment('views_count');
        return view('faq.show', compact('knowledgeBase'));
    }
}
