<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    protected $table='aletr';
    public function site()
    {
        return $this->hasOne('App\Models\Site', 'id', 'site_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
