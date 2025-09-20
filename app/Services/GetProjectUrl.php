<?php

namespace App\Services;

class GetProjectUrl
{
    public static function fromPath($path): string
    {
        preg_match("#(?<=^project/)[a-zA-Z0-9]{10}(?=($|/))#", $path, $matches);
        if (empty($matches)) {
            return redirect('cabinet');
        }
        return $matches[0];
    }
}
