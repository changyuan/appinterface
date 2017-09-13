<?php

namespace App\Models\v2;

use App\Models\BaseModel;

use DB;

class GoodsVideo extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'goods_video';

    public    $timestamps = false;

    protected $visible = [ 'video', 'tag', 'sorts','default'];

    protected $appends = [ 'video', 'tag','sorts','default'];


    public function getGoodsAttribute()
    {
        return $this->goods_id;
    }

    public function getVideoAttribute()
    {
    	return Video::where('id',$this->video_id)->first()->toArray();
    }

    public function getTagAttribute()
    {
    	return Tags::where('tag_id',$this->tag_id)->first()->toArray()['name'];
    }

    public function getSortsAttribute()
    {
        return $this->sort;
    }

    public function getDefaultAttribute()
    {
        return $this->is_default;
    }

}