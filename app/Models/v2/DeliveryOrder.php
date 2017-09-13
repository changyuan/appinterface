<?php

namespace App\Models\v2;

use App\Models\BaseModel;

use App\Helper\Token;

class DeliveryOrder extends BaseModel
{
	protected $connection = 'shop';

	protected $table = 'delivery_order';

	protected $primaryKey = 'delivery_id';

	public $timestamps = false;

	protected $visible = ['order_id','order_sn','invoice_no','shipping_id','shipping_name'];
    // protected $appends = ['player','pic','time'];

}
