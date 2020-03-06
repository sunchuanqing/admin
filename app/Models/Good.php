<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    // 一对一关联商品类别id
    public function cat_id()
    {
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
}
