<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Http\UploadedFile;

interface GoogleRecaptchaRepositoryInterface
{
    public function verify(string $token, string $ipaddr);
}
