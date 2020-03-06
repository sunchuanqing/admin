<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop_serve extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    // 一对一关联服务类别id
    public function serve_type_id()
    {
        return $this->hasOne('App\Models\Shop_serve_type', 'id', 'serve_type_id');
    }
}
