<?php

namespace App\Jobs;

use App\Classes\CReceiptCheck;
use App\Classes\CResult;
use App\Events\CanceledEvent;
use App\Events\RenewedEvent;
use App\Events\StartedEvent;
use App\Models\Device;
use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPurchases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public $tries = 3;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // son iki basamak 6 ya bölünüyorsa hata fırlat
        if (CReceiptCheck::chamber($this->data->receipt)) {
            // rate limit hatası 1 saat sonra tekrar deneyecek
            Log::info($this->data->id . 'rate-limit takıldı 1 saat sonra denenecek');
            $this->release(3600);
        } else {
            // Check Receipt OS
            if ($this->data->os == 'ios') {
                $verify = CReceiptCheck::ios($this->data->receipt);
            } else if ($this->data->os == 'android') {
                $verify = CReceiptCheck::android($this->data->receipt);
            }
            // event
            $event = [
                'appID' => $this->data->app_id,
                'deviceID' => $this->data->device_id,
            ];
            if ($verify['status']) {
                Purchase::where([
                    'id' => $this->data->id
                ])->update([
                    'expire-date' => $verify['expire-date'],
                    'status' => 1
                ]);
                Log::info($this->data->id . ' Abonelik başarıyla güncellendi');
                event(new StartedEvent($this->data->endpoint, $event));
            } else {
                Purchase::where([
                    'id' => $this->data->id
                ])->update([
                    'expire-date' => $verify['expire-date'],
                    'status' => 0
                ]);
                Log::info($this->data->id . ' Abonelik iptal edildi.');
                event(new CanceledEvent($this->data->endpoint, $event));
            }
        }
    }
}
