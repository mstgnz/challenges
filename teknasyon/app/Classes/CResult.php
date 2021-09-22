<?php

namespace App\Classes;

class CResult
{

    static function success($result = [], $message = "Başarılı", $code = 200)
    {
        $response = [
            "status"    => 'success',
            "message"   => $message,
            "result"    => $result
        ];
        return response()->json($response, $code);
    }

    static function error($result = [], $message = "Hata", $code = 200)
    {
        $response = [
            "status"    => 'error',
            "message"   => $message,
            "result"    => $result
        ];
        return response()->json($response, $code);
    }
}
