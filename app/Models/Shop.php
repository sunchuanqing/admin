<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    // 多对一关联门店类别
    public function shop_type()
    {
        return $this->belongsTo('App\Models\Shop_type');
    }
}
