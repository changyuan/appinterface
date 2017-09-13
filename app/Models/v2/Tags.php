<?php

namespace App\Models\v2;

use App\Models\BaseModel;

class Tags extends BaseModel
{
    protected $connection = 'shop';

    protected $table      = 'tag';

    public    $timestamps = false;

    protected $visible = ['id', 'name'];

    protected $appends = ['id', 'name'];

    protected $guarded = [];


    public function getIdAttribute()
    {
        return $this->tag_id;
    }

    public function getNameAttribute()
    {
        return $this->tag_words;
    }

    public function getRelationAttribute()
    {
        return time();
    }

    public function getTypesAttribute()
    {
        return $this->type;
    }

}