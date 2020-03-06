<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public function coupon_types()
    {
        return $this->hasOne('App\Models\Coupon_type', 'coupon_id', 'id');
    }
}
