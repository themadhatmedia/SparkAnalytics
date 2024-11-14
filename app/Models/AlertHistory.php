<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertHistory extends Model
{
    use HasFactory;
    protected $table='aletr_history';
    public function site()
    {
        return $this->hasOne('App\Models\Site', 'id', 'site_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
    public function detail()
    {
        return $this->hasOne('App\Models\Alert', 'id', 'aletr_id');
    }
    
}
