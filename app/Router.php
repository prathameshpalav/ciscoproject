<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Router extends Model
{
    use SoftDeletes;
    
    public function scopeIp($query, $value) {
        return $query->where('hostname', $value);
    }
}
