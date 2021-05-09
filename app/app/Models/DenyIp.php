<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DenyIp extends Model
{
    use HasFactory;

    public $fillable = [
        'ip',
        'is_tor'
    ];

    protected $casts = [
        'is_tor' => 'boolean'
    ];
}
