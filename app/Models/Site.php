<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $table='site';

    public function widget()
    {
        return $this->hasMany('App\Models\Widget', 'site_id', 'id');
    }
    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

}
