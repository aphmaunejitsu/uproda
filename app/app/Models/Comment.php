<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'image_id',
        'comment',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
