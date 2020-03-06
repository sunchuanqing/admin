<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    // 多对一关联专题类别
    public function topic_type()
    {
        return $this->belongsTo('App\Models\Topic_type');
    }
}
