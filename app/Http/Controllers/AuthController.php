<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorizeUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('login.auth');
    }

    public function authorizeUser(AuthorizeUserRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/cabinet');
        }
        return back()->withErrors([
            'login' => 'Неверные имя пользователя или пароль.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/login');
    }
}
