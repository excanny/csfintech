<?php

namespace App\Classes;


class Security
{
    public static function parsePinFromApp($data)
    {
        $data = base64_decode($data);
        $data = explode(env('SERVER_KEY'), $data)[1];
        $data = base64_decode($data);

        return $data;
    }
}
