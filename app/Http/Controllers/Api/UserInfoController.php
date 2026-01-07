<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserInfoRequest;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInfoController extends Controller
{
    public function show()
    {
        $user_id = Auth::id();

        $user_info = UserInfo::where('user_id', $user_id)
            ->select('name', 'surname', 'patronymic', 'about', 'contact_email', 'phone', 'avatar_img')
            ->first();

        return response()->json([
            'success' => true,
            'userInfo' => $user_info,
        ]);
    }

    public function update(SaveUserInfoRequest $request)
    {
        $user_id = Auth::id();
        $validate_data = $request->validated();

        try {
            if ($request->hasFile('avatar_file')) {
                $file = $request->file('avatar_file');
                $file_name = 'user-' . Auth::id() . '-avatar' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('img/avatars/'), $file_name);
                $path = '/img/avatars/' . $file_name;

                $validate_data['avatar_img'] = $path;
            }

            $user_info = UserInfo::where('user_id', $user_id)->firstOrFail();
            $user_info->update($validate_data);

            return response()->json([
                'success' => true,
                'userInfo' => $user_info->fresh()->only([
                    'name',
                    'surname',
                    'patronymic',
                    'about',
                    'contact_email',
                    'phone',
                    'avatar_img'
                ]),
                'message' => 'Профиль успешно обновлён.',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
