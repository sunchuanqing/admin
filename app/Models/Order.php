<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // 一对多关联订单商品
    public function order_goods()
    {
        return $this->hasMany('App\Models\Order_good', 'order_sn', 'order_sn');
    }
    // 一对多关联订单操作日志
    public function order_action()
    {
        return $this->hasMany('App\Models\Order_action', 'order_sn', 'order_sn');
    }
}
