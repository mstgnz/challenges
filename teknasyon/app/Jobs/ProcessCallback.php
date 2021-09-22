<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $endpoint, $event;
    public $tries = 3;
    public function __construct($endpoint, $event)
    {
        $this->endpoint = $endpoint;
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::timeout(3)->post($this->endpoint, $this->event);
        if ($response->successful()) {
            Log::info('Callback Başarılı');
        } else {
            // 1 saat arayla tekrar dener 3 işlemden sonra denemeyi bırakır.
            $this->release(3600);
            Log::info('Callback cevap alınamadı 1 saat sonra tekrar denenecek');
        }
    }
}
