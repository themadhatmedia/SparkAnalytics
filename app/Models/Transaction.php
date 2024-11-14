<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = [
        'referrance_code',
        'used_referrance',
        'company_name',
        'plan_name',
        'plan_price',
        'plan_commission_rate',
        'threshold_amount',
        'commission',
        'uid',
    ];

    public function RefcompanyName()
    {
        return $this->hasOne('App\Models\User', 'referrance_code', 'used_referrance');
    }
}
