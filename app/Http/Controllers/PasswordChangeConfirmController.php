<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PasswordChangeConfirmController extends Controller
{
    public function confirm($token)
    {
        $user = User::where('pending_password_token', $token)->first();

        if (!$user) {
            return redirect()->route('home')->with('error', 'Неверная или устаревшая ссылка для подтверждения.');
        }

        // Обновляем пароль
        $user->password = $user->pending_password;
        $user->pending_password = null;
        $user->pending_password_token = null;
        $user->save();

        // Логиним пользователя
        auth()->login($user);

        return redirect()->route('home')->with('success', 'Ваш пароль был успешно изменён.');
    }
}