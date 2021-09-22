<?php

namespace App\Http\Controllers;

use App\Classes\CResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $reports = DB::select("SELECT apps.NAME,
        Date_format(purchases.updated_at, '%Y-%m-%d') AS 'DAY',
        SUM(CASE WHEN devices.os = 'ios' AND purchases.status = '1' AND purchases.created_at = purchases.updated_at THEN 1 ELSE 0 END) AS 'IOS-Start',
        SUM(CASE WHEN devices.os = 'ios' AND purchases.status = '1' AND purchases.created_at != purchases.updated_at  THEN 1 ELSE 0 END) AS 'IOS-Update',
        SUM(CASE WHEN devices.os = 'ios' AND purchases.status = '0' THEN 1 ELSE 0 END) AS 'IOS-Cancel',
        SUM(CASE WHEN devices.os = 'android' AND purchases.status = '1' AND purchases.created_at = purchases.updated_at THEN 1 ELSE 0 END) AS 'ANDROID-Start',
        SUM(CASE WHEN devices.os = 'android' AND purchases.status = '1' AND purchases.created_at != purchases.updated_at  THEN 1 ELSE 0 END) AS 'ANDROID-Update',
        SUM(CASE WHEN devices.os = 'android' AND purchases.status = '0' THEN 1 ELSE 0 END) AS 'ANDROID-Cancel',
        Count(purchases.id)                           AS 'TOTAL'
 FROM   apps
        INNER JOIN devices
                ON devices.app_id = apps.id
        INNER JOIN purchases
                ON purchases.device_id = devices.id
 GROUP  BY apps.NAME,
           DAY");
        return CResult::success($reports);
    }
}
