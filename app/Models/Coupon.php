<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'code',
        'discount',
        'limit',
        'description',
    ];

    public function used_coupon()
    {
        return $this->hasMany('App\Models\UserCoupon', 'coupon', 'id')->count();
    }
    public function coupen_details($id)
    {
        $coupen=Coupon::where('id',$id)->first();
        return $coupen->name;
    }
}
