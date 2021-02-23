<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $fillable = [
        'image_hash_id',
        'basename',
        'ext',
        't_ext',
        'original',
        'delkey',
        'mimetype',
        'size',
        'comment',
        'ip',
        'width',
        'height',
    ];

    public function imageHash()
    {
        return $this->belongsTo(ImageHash::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
