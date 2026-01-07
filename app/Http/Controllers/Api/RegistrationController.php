<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserInfoRequest;
use App\Http\Requests\TmpSaveUserRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\SaveImg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;


class RegistrationController extends Controller
{
    public function tmpSaveUser(TmpSaveUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validated();

        $raw_token = Str::uuid()->toString();
        $hash_token = hash('sha256', $raw_token);
        Redis::setex('registration:' . $hash_token, 120, json_encode([
            'email' => $validate_data['email'],
            'password' => Hash::make($validate_data['password']),
        ]));

        return response()->json([
            'success' => true,
            'token' => $raw_token,
        ]);
    }

    public function registerUser(SaveUserInfoRequest $request): \Illuminate\Http\JsonResponse
    {
        $raw_token = $request->token;
        $hash_token = hash('sha256', $raw_token);

        $data = Redis::get('registration:' . $hash_token);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Token expired. Restart registration.',
            ]);
        }

        $validate_data = $request->validated();

        $user_data = json_decode($data, true);

        if($request->hasFile('avatar_file')) {
            $path = SaveImg::userAvatar($request->file('avatar_file'));
        } else {
            $path = '/img/avatars/to-do.png';
        }

        $validate_data['avatar_img'] = $path;

        $user = User::create([
            'email' => $user_data['email'],
            'password' => $user_data['password'],
        ]);

        $user_id = $user->id;

        $validate_data['user_id'] = $user_id;

        UserInfo::create($validate_data);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'userData' => [
                'id' => $user_id,
                'email' => $user_data['email'],
            ],
        ]);
    }

}
