<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Просмотр профиля другого пользователя
    public function show($id)
    {
        $user = User::with(['posts', 'comments'])->findOrFail($id);

        $postsCount = $user->posts()->count();
        $commentsCount = $user->comments()->count();

        $likesGiven = Like::where('user_id', $user->id)->count();

        $postIds = $user->posts()->pluck('id');
        $likesReceivedOnPosts = Like::where('likeable_type', 'App\Models\Post')
            ->whereIn('likeable_id', $postIds)->count();

        $commentIds = $user->comments()->pluck('id');
        $likesReceivedOnComments = Like::where('likeable_type', 'App\Models\Comment')
            ->whereIn('likeable_id', $commentIds)->count();

        $totalLikesReceived = $likesReceivedOnPosts + $likesReceivedOnComments;
        $lastSeen = $user->last_seen_at ? $user->last_seen_at->diffForHumans() : 'Никогда';

        $recentPosts = $user->posts()->orderBy('created_at', 'desc')->limit(5)->get();
        $recentComments = $user->comments()->orderBy('created_at', 'desc')->limit(5)->get();

        return view('profile.show', compact(
            'user', 'postsCount', 'commentsCount', 'likesGiven', 'totalLikesReceived',
            'lastSeen', 'recentPosts', 'recentComments'
        ));
    }

    // Форма редактирования профиля (только для владельца)
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Обновление профиля
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Обработка аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path; // сохраняем относительный путь, например "avatars/filename.jpg"
        }

        $user->save();

        return redirect()->route('profile.show', $user->id)->with('success', 'Профиль обновлён');
    }

    // Блокировка / разблокировка пользователя (только для админа)
    public function toggleBan($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = !$user->is_banned;
        $user->save();
        return back()->with('success', 'Статус пользователя изменён');
    }
}
