<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use App\Services\UserInfoValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function registration()
    {
        return view('login.register');
    }

    public function tmpSaveUser(Request $request)
    {
        $validate_data = $request->validate([
            'email' => 'required|email|bail|unique:users|max:30',
            'password' => 'required|min:8|max:25',
            'confirm_password' => 'required|same:password',
        ], [
            'email.required' => 'Email обязателен.',
            'email.email' => 'Укажите корректный email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'email.max' => 'Максимальная длина 30 символов.',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Минимальная длина 8 символов',
            'password.max' => 'Максимальная длина 25 символов',
            'confirm_password.required' => 'Подтвердите пароль',
            'confirm_password.same' => 'Пароли не совпадают'
        ]);

        $validate_data['password'] = bcrypt($validate_data['password']);
        unset($validate_data['confirm_password']);
        session()->put('register_data', $validate_data);

        return redirect('/fulfill-profile');
    }

    public function fulfillProfile(Request $request)
    {
        if (session()->has('register_data')) {
            return view('userInfo.createNChange');
        }

        return redirect('/registration');
    }

    public function registerUser(Request $request)
    {
        $register_data = session()->get('register_data');

        $validate = UserInfoValidator::check($request->all());

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }

        $valid_data = $validate->validated();

        if ($request->hasFile('avatar_img')) {
            $file = $request->file('avatar_img');
            $file_name = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/avatars/'), $file_name);
            $path = '/img/avatars/' . $file_name;
        } else {
            $path = '/img/avatars/to-do.png';
        }

        $valid_data['avatar_img'] = $path;

        $user = User::create([
            'email' => $register_data['email'],
            'password' => $register_data['password'],
        ]);

        $valid_data['user_id'] = $user->id;

        UserInfo::create($valid_data);


        Auth::login($user);

        return redirect('/cabinet');
    }
}
