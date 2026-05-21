<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumSection;
use Illuminate\Http\Request;

class AdminSectionController extends Controller
{
    public function index(Request $request)
    {
        $query = ForumSection::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $sections = $query->orderBy('id')->paginate(15);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:forum_sections,name',
            'description' => 'nullable|string',
        ]);
        ForumSection::create($request->all());
        return redirect()->route('admin.sections.index')->with('success', 'Раздел создан');
    }

    public function edit(ForumSection $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, ForumSection $section)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:forum_sections,name,' . $section->id,
            'description' => 'nullable|string',
        ]);
        $section->update($request->all());
        return redirect()->route('admin.sections.index')->with('success', 'Раздел обновлён');
    }

    public function destroy(ForumSection $section)
    {
        if ($section->posts()->count()) {
            return back()->with('error', 'В разделе есть посты, сначала удалите их.');
        }
        $section->delete();
        return back()->with('success', 'Раздел удалён');
    }
}
