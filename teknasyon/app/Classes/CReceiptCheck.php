<?php

namespace App\Classes;

class CReceiptCheck
{

    // IOS Receipt Verify
    public static function ios($receipt)
    {
        $staus = self::checkLastCharacterIsSignle($receipt);
        return self::verify($staus);
    }

    // Google Receipt Verify
    public static function android($receipt)
    {
        $staus = self::checkLastCharacterIsSignle($receipt);
        return self::verify($staus);
    }

    // Response Verify
    private static function verify($status)
    {
        return [
            'status' => $status,
            'expire-date'  => date('Y-m-d H:i:s', strtotime('+1 month'))
        ];
    }

    // Last Character Control
    private static function checkLastCharacterIsSignle($receipt)
    {
        $last = substr($receipt, -1);
        if (is_numeric($last) && $last % 2 != 0) {
            return true;
        }
        return false;
    }

    // son 2 basamak 6 ya bölünüyorsa rate-limit hatası ver
    public static function chamber($receipt)
    {
        $last = substr($receipt, -2);
        if (is_numeric($last) && $last % 6 == 0) {
            return false;
        }
        return true;
    }
}
