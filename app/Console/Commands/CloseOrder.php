<?php
//
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Models\V2\Order;

class CloseOrder extends Command{

    protected $signature = 'close:order';

    protected $description = 'close order status by time';

    public function handle()
    {
        $model = Order::closeOrder();
    }
}