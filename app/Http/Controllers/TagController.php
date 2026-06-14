<?php

namespace App\Http\Controllers;

use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Показать все посты с указанным тегом.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        $posts = $tag->posts()->with(['user', 'section', 'tags'])->paginate(15);

        return view('forum.tag', compact('tag', 'posts'));
    }
}
