<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'file_size',
        'format',
        'exported_at',
        'ip_address',
        'is_bulk'
    ];
    
    protected $casts = [
        'exported_at' => 'datetime',
        'is_bulk' => 'boolean'
    ];
}