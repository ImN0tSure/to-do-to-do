<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserInfoRequest;
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
        abort('404');
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
        abort('404');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_info = [
            'user' => UserInfo::where('user_id', $id)->first(),
        ];

        return view('userInfo.show', $user_info);
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
            return redirect('cabinet');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveUserInfoRequest $request)
    {
        $validate_data = $request->validated();

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
        abort('404');
    }
}
