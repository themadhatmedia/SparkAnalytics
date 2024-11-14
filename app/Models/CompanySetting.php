<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    protected $table='Company_settings';
    protected $fillable = [
        'name',
        'value',
        'created_by'
    ];
}
