<?php

namespace App\Helpers;

use App\Models\UserUniqueNumber;


class UniqueNumberHelper
{
    public static function generateUniqueNumber()
    {
        do {
            $number = self::generateNumber();
        } while (UserUniqueNumber::where('unique_number', $number)->exists());

        return $number;
    }

    private static function generateNumber()
    {
        $part1 = rand(1000, 9999);
        $part2 = rand(1000, 9999);
        $part3 = rand(1000, 9999);

        return "$part1-$part2-$part3";
    }

    public static function saveUniqueNumber($userId)
    {
        $uniqueNumber = self::generateUniqueNumber();
        UserUniqueNumber::create([
            'user_id' => $userId,
            'unique_number' => $uniqueNumber,
        ]);

        return $uniqueNumber;
    }
}
