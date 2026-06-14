<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

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

    // Обновление профиля (только имя, email, аватар)
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Обработка аватара
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();
        return redirect()->route('profile.show', $user->id)->with('success', 'Профиль обновлён');
    }

    // Отправка ссылки для смены пароля (из профиля)
    public function sendResetLink(Request $request)
    {
        $user = auth()->user();

        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['email' => $user->email, 'token' => bcrypt($token), 'created_at' => now()]
        );

        $user->sendPasswordResetNotificationFromProfile($token);

        return back()->with('success', 'Ссылка для сброса пароля отправлена на ваш email.');
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