<?php

namespace App\Services;

use Carbon\Carbon;

class HowMuchTime
{
    public static function expiresIn($end_date): string
    {
        $now = Carbon::now();
        $end = Carbon::parse($end_date);

        $days = $now->diffInDays($end);

        $sign = '';
        if ($end->isBefore($now)) {
            $sign = '-';
        }
        $response = self::ruWordForm($days);
        if ($days < 2) {
            $hours = $now->diffInHours($end);
            $response = self::ruWordForm($hours, 'hours');
        }

        return $sign . $response;
    }

    protected static function ruWordForm(int $num, $word = 'days'): string
    {
        $words = [
            'days' => [
                'день',
                'дня',
                'дней'
            ],
            'hours' => [
                'час',
                'часа',
                'часов'
            ]
        ];

        if ($num >= 5 and $num <= 20) {
            return $num . ' ' . $words[$word][2];
        } else {
            $numStr = (string)$num;
            $units = substr($numStr, -1, 1);

            if ($units == '1') {
                return $numStr . ' ' . $words[$word][0];
            } elseif($units == '2' || $units == '3' || $units == '4') {
                return $numStr . ' ' . $words[$word][1];
            } else {
                return $numStr . ' ' . $words[$word][2];
            }
        }
    }
}
