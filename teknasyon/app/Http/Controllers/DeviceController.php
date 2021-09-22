<?php

namespace App\Http\Controllers;

use App\Classes\CResult;
use App\Models\App;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'app_id' => 'required',
            'language' => 'required',
            'os' => 'required|in:ios,android',
        ]);
        if ($validator->fails()) {
            return CResult::error($validator->errors(), "error", 400);
        } else {
            // app kontrolü
            $app = App::where('id', $request->app_id)->exists();
            if ($app) {
                $device = [
                    'uid' => $request->uid,
                    'app_id' => $request->app_id,
                    'language' => $request->language,
                    'os' => $request->os,
                    'token' => $this->createToken()
                ];
                $deviceInsert = Device::updateOrCreate(
                    [
                        'uid' => $request->uid,
                        'app_id' => $request->app_id
                    ],
                    $device
                );
                if ($deviceInsert) {
                    $deviceResponseData = [];
                    $deviceResponseData['register'] = 'OK';
                    $deviceResponseData['client-token'] = $deviceInsert->token;
                    return CResult::success($deviceResponseData, 'OK', 200);
                } else {
                    return CResult::error([], 'Device oluşturulamadı.', 400);
                }
            } else {
                return CResult::error([], 'App bulunamadı.', 400);
            }
        }
    }
    // Devices listesi
    public function index(Request $request)
    {
        $devices = Device::all();
        return CResult::success($devices);
    }

    // Devices bilgileri
    public function show(Request $request)
    {
        $device = Device::where('id', $request->id)->first();
        if ($device) {
            return CResult::success($device);
        } else {
            return CResult::error([], 'Device Bulunamadı', 400);
        }
    }
    private function createToken()
    {
        //benzersiz token oluşturuluyor 
        $token = Str::random(60) . time();
        return hash('sha256', $token);
    }
}
