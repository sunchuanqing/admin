<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon_user extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'coupon_sn',
        'money',
        'full_money',
        'discount',
        'coupon_name',
        'coupon_img',
        'coupon_start_time',
        'coupon_end_time',
        'coupon_type',
        'coupon_type_name',
        'status',
        'subject_type',
        'shop_id',
        'usable_range',
        'else_msg',
        'bc_msg',
        'created_at',
        'updated_at'
    ];
}
