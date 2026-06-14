<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Сохранение нового комментария (корневого или ответа)
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $post = Post::findOrFail($request->post_id);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'commentable_id' => $post->id,
            'commentable_type' => 'App\Models\Post',
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Комментарий добавлен');
    }

    // Форма редактирования (возвращает view для inline)
    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        // Проверка прав: автор, модератор или админ
        if (Auth::id() !== $comment->user_id && !Auth::user()->isModerator() && !Auth::user()->isAdmin()) {
            abort(403, 'Нет прав для редактирования');
        }
        return view('forum._edit_comment', compact('comment'));
    }

    // Обновление комментария
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        if (Auth::id() !== $comment->user_id && !Auth::user()->isModerator() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);
        $comment->content = $request->content;
        $comment->save();
        return redirect()->back()->with('success', 'Комментарий обновлён');
    }

    // Мягкое удаление комментария
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        if (Auth::id() !== $comment->user_id && !Auth::user()->isModerator() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        $comment->delete(); // заполнится deleted_at
        return redirect()->back()->with('success', 'Комментарий удалён');
    }

    // Восстановление комментария (если нужно)
    public function restore($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        if (Auth::user()->isAdmin() || Auth::user()->isModerator()) {
            $comment->restore();
            return redirect()->back()->with('success', 'Комментарий восстановлен');
        }
        abort(403);
    }
    
    public function __construct()
{
    $this->middleware(['auth', 'verified'])->except(['edit', 'update', 'destroy']); // можно убрать except, тогда редактирование тоже требует верификации
}
}
