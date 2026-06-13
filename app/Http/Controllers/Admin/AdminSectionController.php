<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSectionController extends Controller
{
    public function index()
    {
        $sections = ForumSection::orderBy('id')->paginate(15);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:forum_sections,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('section_images', 'public');
            $data['image_url'] = $path;
        }

        ForumSection::create($data);
        return redirect()->route('admin.sections.index')->with('success', 'Раздел создан');
    }

    public function edit(ForumSection $section)
    {
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, ForumSection $section)
    {
        $request->validate([
            'name' => 'required|max:100|unique:forum_sections,name,' . $section->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('image')) {
            if ($section->image_url && Storage::disk('public')->exists($section->image_url)) {
                Storage::disk('public')->delete($section->image_url);
            }
            $path = $request->file('image')->store('section_images', 'public');
            $data['image_url'] = $path;
        }

        $section->update($data);
        return redirect()->route('admin.sections.index')->with('success', 'Раздел обновлён');
    }

    public function destroy(ForumSection $section)
    {
        if ($section->image_url && Storage::disk('public')->exists($section->image_url)) {
            Storage::disk('public')->delete($section->image_url);
        }
        $section->delete();
        return back()->with('success', 'Раздел удалён');
    }
}
