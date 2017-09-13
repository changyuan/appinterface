<?php
//
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\V2\Order;

class CloseTrade extends Command{

    protected $signature = 'close:trade';

    protected $description = 'close trade status by time';

    public function handle()
    {
        $model = Order::closeTrade();
    }
}