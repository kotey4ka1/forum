<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBase;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    // Публичный список статей для пользователей
    public function index()
    {
        $articles = KnowledgeBase::where('is_published', true)->orderBy('category')->paginate(15);
        return view('faq.index', compact('articles'));
    }

    // Публичный просмотр одной статьи
    public function show(KnowledgeBase $knowledgeBase)
    {
        $knowledgeBase->increment('views_count');
        return view('faq.show', compact('knowledgeBase'));
    }
}
