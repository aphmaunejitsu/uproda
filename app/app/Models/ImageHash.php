<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageHash extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'hash',
        'ng',
        'comment',
    ];

    protected $casts = [
        'ng' => 'boolean'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
