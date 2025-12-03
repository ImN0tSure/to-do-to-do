<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserInfoRequest;
use App\Http\Requests\TmpSaveUserRequest;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function registration()
    {
        return view('login.register');
    }

    public function tmpSaveUser(TmpSaveUserRequest $request)
    {
        $validate_data = $request->validated();

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

    public function registerUser(SaveUserInfoRequest $request)
    {
        $register_data = session()->get('register_data');

        $validate_data = $request->validated();

        if ($request->hasFile('avatar_img')) {
            $file = $request->file('avatar_img');
            $file_name = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/avatars/'), $file_name);
            $path = '/img/avatars/' . $file_name;
        } else {
            $path = '/img/avatars/to-do.png';
        }

        $validate_data['avatar_img'] = $path;

        $user = User::create([
            'email' => $register_data['email'],
            'password' => $register_data['password'],
        ]);

        $validate_data['user_id'] = $user->id;

        UserInfo::create($validate_data);

        Auth::login($user);

        return redirect('/cabinet');
    }
}
