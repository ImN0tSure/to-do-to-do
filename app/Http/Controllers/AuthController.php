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

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'user' => $request->user(),
                    'session' => session()->getId(),
                ]);
            } else {
                return redirect()->intended('/cabinet');
            }

        }

        if ($request->expectsJson()) {
            return response()->json([
                "success" => false,
                'message' => 'Неверные имя пользователя или пароль.'
            ], 422);
        } else {
            return back()->withErrors([
                'login' => 'Неверные имя пользователя или пароль.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Выход выполнен.',
            ]);
        } else {
            return redirect('/login');
        }
    }
}
