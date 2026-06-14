<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/';

    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        $source = $request->input('source');

        if ($response == Password::PASSWORD_RESET) {
            if ($source === 'profile') {
                return redirect()->route('profile.show', $request->user()->id)->with('success', 'Пароль успешно изменён!');
            }
            return redirect()->route('home')->with('success', 'Пароль успешно изменён!');
        }

        return $this->sendResetFailedResponse($request, $response);
    }
}