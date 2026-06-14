<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|in:post,comment',
            'id' => 'required|integer'
        ]);

        $type = $request->type;
        $id = $request->id;

        if ($type === 'post') {
            $object = Post::findOrFail($id);
            $likeableType = 'App\Models\Post';
        } else {
            $object = Comment::findOrFail($id);
            $likeableType = 'App\Models\Comment';
        }

        $like = Like::where('user_id', Auth::id())
            ->where('likeable_id', $id)
            ->where('likeable_type', $likeableType)
            ->first();

        if ($like) {
            $like->delete();
            $object->decrement('likes_count');
            $liked = false;
        } else {
            Like::create([
                'user_id' => Auth::id(),
                'likeable_id' => $id,
                'likeable_type' => $likeableType,
            ]);
            $object->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likesCount' => $object->likes_count
        ]);
    }
    
    public function __construct()
{
    $this->middleware(['auth', 'verified']);
}
}
