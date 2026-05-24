<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseAdminController extends Controller
{
    // Список статей (админка)
    public function index(Request $request)
    {
        $query = KnowledgeBase::query();

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('question', 'like', '%' . $request->search . '%')
                    ->orWhere('answer', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        $articles = $query->orderBy('category')->paginate(20)->appends($request->query());

        return view('admin.faq.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string',
            'answer' => 'required|string',
            'is_published' => 'sometimes|boolean',
        ]);

        KnowledgeBase::create([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
            'is_published' => $request->has('is_published'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.faq.index')->with('success', 'Статья создана');
    }

    public function edit(KnowledgeBase $faq)
    {
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, KnowledgeBase $faq)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'question' => 'required|string',
            'answer' => 'required|string',
            'is_published' => 'sometimes|boolean',
        ]);

        $faq->update([
            'category' => $request->category,
            'question' => $request->question,
            'answer' => $request->answer,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.faq.index')->with('success', 'Статья обновлена');
    }

    public function destroy(KnowledgeBase $faq)
    {
        $faq->delete();
        return back()->with('success', 'Статья удалена');
    }
}
