<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;
    protected $table = 'payouts';
    protected $fillable = [
        'company_id',
        'amount',
        'date',
        'status',
        'referrance_code',
    ];


    public function companyName()
    {
        return $this->hasOne('App\Models\User', 'id', 'company_id');
    }

}
