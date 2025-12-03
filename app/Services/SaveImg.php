<?php

namespace App\Services;

class SaveImg
{
    public static function userAvatar($file): string {
        $file_name = 'avatar_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('img/avatars/'), $file_name);

        return '/img/avatars/' . $file_name;
    }
}
