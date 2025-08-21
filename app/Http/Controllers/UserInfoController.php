<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'userInfo index';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        view('userInfo.createNChange');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = [
            'user' => UserInfo::where('user_id', $id)->first(),
        ];

        return view('userInfo.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ($id == Auth::id()) {
            $user_info = UserInfo::where('user_id', Auth::id())->first();

            return view('userInfo.createNChange', ['user_info' => $user_info]);
        } else {
            return view('cabinet.main');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Переписать с использованием сервиса UserInfoValidator
        $validate_data = $request->validate([
            'avatar_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|bail',
            'surname' => 'required|string|max:20|bail',
            'name' => 'required|string|max:20|bail',
            'patronymic' => 'nullable|string|max:20|bail',
            'phone' => 'nullable|string|max:20|bail',
            'contact_email' => 'required|email|max:30|bail',
            'about' => 'nullable|string|max:1500|bail',
        ], [
            'avatar_img.mimes' => 'Поддерживаются следующие форматы: jpeg, png, jpg, gif, svg',
            'avatar_img.max' => 'Максимальный размер 2048 байт',
            'avatar_img.image' => 'Вставьте картинку',
            'surname.required' => 'Это поле обзяательно для заполнения',
            'surname.max' => 'Максимальная длина 20 символов',
            'name.required' => 'Это поле обзяательно для заполнения',
            'name.max' => 'Максимальная длина 20 символов',
            'patronymic.max' => 'Максимальная длина 20 символов',
            'phone.max' => 'Максимальная длина 20 символов',
            'contact_email.required' => 'Email для связи обязателен',
            'contact_email.email' => 'Укажите корректный email',
            'contact_email.max' => 'Максимальная длина 30 символов',
            'about.max' => 'Максимальная длина 1500 символов',
        ]);

        if ($request->hasFile('avatar_img')) {
            $file = $request->file('avatar_img');
            $file_name = 'user-' . Auth::id() . '-avatar' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/avatars/'), $file_name);
            $path = '/img/avatars/' . $file_name;

            $validate_data['avatar_img'] = $path;
        }

        UserInfo::where('user_id', Auth::id())->update($validate_data);

        return redirect()->route('cabinet');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
