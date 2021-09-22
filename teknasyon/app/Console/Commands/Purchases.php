<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPurchases;
use App\Models\Purchase;
use Illuminate\Console\Command;

class Purchases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // zamanı dolanları kuyruk sırasına ekleniyor.
        $purchases = Purchase::select([
            'purchases.id',
            'purchases.device_id',
            'purchases.receipt',
            'devices.app_id',
            'devices.os',
            'apps.endpoint'
        ])->join('devices', 'devices.id', '=', 'purchases.device_id')
            ->join('apps', 'apps.id', '=', 'devices.app_id')
            ->where('status', '=', 1)
            ->whereDate('expire-date', '<=', date('Y-m-d H:i:s', strtotime('+1 month')))
            ->get();
        foreach ($purchases as $key => $value) {
            ProcessPurchases::dispatch(json_decode($value->toJson()));
        }
    }
}
