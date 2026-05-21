<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        if ($request->filled('is_banned')) {
            $query->where('is_banned', $request->is_banned == '1');
        }
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = \App\Models\Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $isSelf = auth()->id() == $user->id;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if (!$isSelf) {
            $rules['role_id'] = 'required|exists:roles,id';
            $rules['is_banned'] = 'boolean';
        }

        $validated = $request->validate($rules);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!$isSelf) {
            $data['role_id'] = $request->role_id;
            $data['is_banned'] = $request->has('is_banned');
        } else {
            // Сохраняем старые значения, чтобы администратор не изменил их через подмену запроса
            $data['role_id'] = $user->role_id;
            $data['is_banned'] = $user->is_banned;
        }

        $user->update($data);

        $message = $isSelf ? 'Ваш профиль обновлён' : 'Пользователь обновлён';
        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Пользователь удалён');
    }
}
