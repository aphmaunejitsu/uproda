<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function imageHash()
    {
        return $this->belongsTo(ImageHash::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
