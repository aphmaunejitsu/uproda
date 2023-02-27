<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChunkFile extends Model
{
    use HasFactory;

    public $fillable = [
        'uuid',
        'ip',
        'original',
        'number',
        'is_uploaded',
        'is_fail'
    ];

    protected $casts = [
        'is_uploaded' => 'boolean',
        'is_fail'     => 'boolean',
    ];
}
