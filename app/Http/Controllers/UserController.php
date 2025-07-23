<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        unset($validatedData['confirm_password']);

        User::create($validatedData);
        return redirect(route('userInfo.create'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'user show';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'user' => User::find($id)->first(),
        ];
        return view('user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id)->firstOrFail();

        $validatedData = $request->validate([
            'password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|same:new_password',
        ]);

        if(Hash::check($validatedData['password'], $user->password)){
            $user->password = Hash::make($validatedData['new_password']);
            $user->save();

            return redirect(route('user.index'));

        } else {
            return [
                'status' => 'error',
                'message' => 'Вы ввели неверный старый пароль'
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->destroy($id);
    }
}
