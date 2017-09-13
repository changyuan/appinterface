<?php

namespace App\Models\v2;

use App\Models\BaseModel;

use App\Helper\Token;

class Video extends BaseModel
{
	protected $connection = 'shop';

	protected $table = 'video';

	protected $primaryKey = 'id';

	public $timestamps = false;

	protected $visible = ['player','pic','time'];
    protected $appends = ['player','pic','time'];


	public function getNumberAttribute()
    {
        return $this->id;
    }

    public function getPlayerAttribute()
    {
        return $this->visit_url;
    }

    public function getPicAttribute()
    {
        return $this->screenshot;
    }

    public function getTimeAttribute()
    {
        return $this->duration;
    }
}
