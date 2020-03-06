<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_role extends Model
{
    // 一对一关联门店
    public function shop()
    {
        return $this->hasOne('App\Models\Shop', 'id', 'shop_id');
    }
}
