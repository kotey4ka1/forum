<?php

namespace App\Http\Controllers;
use App\Models\Like;
use App\Models\Post;
use App\Models\ForumSection;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;



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
                // Генерируем уникальное имя
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                // Сохраняем в storage/app/public/post_images
                $path = $image->storeAs('post_images', $filename, 'public');
                // В базу пишем относительный путь (например, post_images/filename.jpg)
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
        ]);
        $post->update($request->only('title', 'content'));
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

    public function suggest(Request $request)
    {
        $query = $request->get('q');
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        // Подсказки из заголовков постов
        $suggestions = Post::where('title', 'like', '%' . $query . '%')
            ->limit(10)
            ->pluck('title');
        return response()->json($suggestions);
    }


}
