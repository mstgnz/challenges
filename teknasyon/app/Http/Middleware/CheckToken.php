<?php

namespace App\Http\Middleware;

use App\Classes\CResult;
use Closure;
use App\Models\Device;
use Illuminate\Support\Facades\Validator;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client-token' => 'required'
            ]);
            if ($validator->fails()) {
                return CResult::error($validator->errors(), "error", 400);
            } else {
                $device = Device::select('devices.*', 'apps.endpoint')
                    ->join('apps', 'apps.id', '=', 'devices.app_id')
                    ->where('token', $request['client-token'])
                    ->first();
                if ($device) {
                    $request->attributes->add(['device_id' => $device->id]);
                    $request->attributes->add(['app_id' => $device->app_id]);
                    $request->attributes->add(['os' => $device->os]);
                    $request->attributes->add(['endpoint' => $device->endpoint]);
                    return $next($request);
                } else {
                    return CResult::error([], 'Geçersiz token.', 400);
                }
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return CResult::error([], 'Beklenmedik bir hata oluştur.', 502);
        }
    }
}
