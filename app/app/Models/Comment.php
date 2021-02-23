<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $fillable = [
        'image_id',
        'comment',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
