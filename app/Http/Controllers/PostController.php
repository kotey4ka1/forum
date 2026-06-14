<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Post;
use App\Models\ForumSection;
use App\Models\PostImage;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;


class PostController extends Controller
{
    public function store(Request $request, $sectionId)
    {
        $section = ForumSection::findOrFail($sectionId);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
            'images' => 'max:5',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'forum_section_id' => $section->id,
            'title' => $request->title,
            'content' => $request->content,
            'views_count' => 0,
            'is_pinned' => false,
            'likes_count' => 0,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Сохраняем в папку storage/app/public/post_images
                $path = $image->store('post_images', 'public');
                // $path будет, например, "post_images/filename.jpg"
                PostImage::create([
                    'post_id' => $post->id,
                    'image_url' => $path,
                    'sort_order' => 0,
                ]);
            }
        }

        return redirect()->route('forum.post', $post->id)->with('success', 'Пост создан');
    }

    public function show($id)
    {

        $post = Post::with(['user', 'comments.user', 'images'])->findOrFail($id);
        $isFavorited = auth()->check() ? auth()->user()->favorites()->where('post_id', $post->id)->exists() : false;

        // Уникальные просмотры через сессию
        if (!session()->has('post_viewed_' . $id)) {
            $post->increment('views_count');
            session()->put('post_viewed_' . $id, true);
        }
        return view('forum.post', compact('post', 'isFavorited'));

    }

    public function create($sectionId)
    {
        $section = ForumSection::findOrFail($sectionId);
        return view('forum.create', compact('section'));
    }

    public function edit(Post $post)
    {
        // проверка прав: только автор или админ/модератор
        if (auth()->id() !== $post->user_id && !auth()->user()->isAdmin() && !auth()->user()->isModerator()) {
            abort(403);
        }
        return view('forum.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if (auth()->id() !== $post->user_id && !auth()->user()->isAdmin() && !auth()->user()->isModerator()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|min:10',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'new_images' => 'max:5',
            'delete_images' => 'array',
            'delete_images.*' => 'exists:post_images,id',
        ]);

        // Обновляем заголовок и текст
        $post->update($request->only('title', 'content'));

        // Удаляем отмеченные изображения
        if ($request->filled('delete_images')) {
            $imagesToDelete = \App\Models\PostImage::whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $image) {
                \Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }

        // Добавляем новые изображения
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('post_images', 'public');
                \App\Models\PostImage::create([
                    'post_id' => $post->id,
                    'image_url' => $path,
                    'sort_order' => 0,
                ]);
            }
        }

        return redirect()->route('forum.post', $post->id)->with('success', 'Пост обновлён');
    }

    public function destroy(Post $post)
    {
        if (auth()->id() !== $post->user_id && !auth()->user()->isAdmin() && !auth()->user()->isModerator()) {
            abort(403);
        }
        $post->delete();
        return redirect()->route('home')->with('success', 'Пост удалён');
    }
    public function toggleFavorite($id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();
        if ($user->favorites()->where('post_id', $id)->exists()) {
            $user->favorites()->detach($id);
            return back()->with('success', 'Удалено из избранного');
        } else {
            $user->favorites()->attach($id);
            return back()->with('success', 'Добавлено в избранное');
        }
    }

    public function favorites()
    {
        $posts = auth()->user()->favorites()->paginate(10);
        return view('forum.favorites', compact('posts'));
    }
    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        if (!auth()->user()->isAdmin() && !auth()->user()->isModerator()) {
            abort(403);
        }
        $post->restore();
        return redirect()->back()->with('success', 'Пост восстановлен.');
    }

    public function __construct()
{
    $this->middleware(['auth', 'verified'])->except(['show']);
}



}
