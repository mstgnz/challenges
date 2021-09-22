<?php

namespace App\Http\Controllers;

use App\Classes\CReceiptCheck;
use App\Classes\CResult;
use App\Events\CanceledEvent;
use App\Events\RenewedEvent;
use App\Events\StartedEvent;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receipt' => 'required',
        ]);
        if ($validator->fails()) {
            return CResult::error($validator->errors(), "error", 400);
        } else {
            // event
            $event = [
                'appID' => $request->get('app_id'),
                'deviceID' => $request->get('device_id'),
            ];

            // Check Receipt OS
            if ($request->get('os') == 'ios') {
                $verify = CReceiptCheck::ios($request->receipt);
            } else if ($request->get('os') == 'android') {
                $verify = CReceiptCheck::android($request->receipt);
            }
            if ($verify['status']) {
                $purchase = Purchase::updateOrCreate(['receipt' => $request->receipt], [
                    'device_id' => $request->get('device_id'),
                    'receipt' => $request->receipt,
                    'expire-date' => $verify['expire-date'],
                    'status' => $verify['status']
                ]);
                if ($purchase['created_at'] == $purchase['updated_at']) {
                    event(new StartedEvent($request->get('endpoint'), $event));
                } else {
                    event(new RenewedEvent($request->get('endpoint'), $event));
                }
                return CResult::success($verify);
            } else {
                event(new CanceledEvent($request->get('endpoint'), $event));
                return CResult::error([], "Satın alma işlemi doğrulanamadı.");
            }
        }
    }
    //
    public function check(Request $request)
    {

        // $request->get('device_id') middleware üzerinden token a göre device geliyor.
        $purchase = Purchase::where('device_id', $request->get('device_id'))
            ->whereDate('expire-date', '>', date('Y-m-d H:i:s'))
            ->first();
        if ($purchase) {
            return CResult::success($purchase, "Abonelik bilgileri");
        } else {
            return CResult::error([], 'Abonelik bulunamadı.');
        }
    }
}
