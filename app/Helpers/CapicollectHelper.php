<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class CapicollectHelper{

    /**
     * @param $name
     * @param $phone
     * @param $email
     * @param $amount
     * @param $description
     * @param $reference
     * @return mixed
     */
    public static function initTransaction($name, $phone, $email, $amount, $description, $reference)
    {
        $post = Http::post('https://capicollect.com/api/v1/debit/card/' . env('CAPI_SLUG'), [
                "name" => $name,
                "phone" => $phone,
                "email" => $email,
                "amount" => $amount,
                "description" => $description,
                "reference" => $reference,
                "redirect" => env('CAPI_RED')
        ]);
        return json_decode($post->getBody()->getContents());
    }

    /**
     * @param $reference
     * @return mixed
     */
    public static function verifyTransaction($reference)
    {
       $post = Http::post('https://capicollect.com/api/v1/debit/card/reference/verify', [
            'merchantId' => 'eyJpdiI6IkRqTXVPMkhPK0ZaamlwT3ViSlVtQlE9PSIsInZhbHVlIjoiWlJiVU42cDlBYktpbzRGT3kzcTFIUT09IiwibWFjIjoiOGFjMGEzMzcwZWU1NTY3NzZkNWM5YTlmYzQxNjg4OGRiMTc2YmQ1MTIyZjMyYjA1Y2RkYjE5NTI3MTBmOTc1MCJ9',
            'paymentReference' => $reference
        ]);
        return json_decode($post->getBody()->getContents());
    }
}
