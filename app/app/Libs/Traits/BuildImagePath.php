<?php

namespace App\Libs\Traits;

use Illuminate\Support\Str;

trait BuildImagePath
{
    public function getImageUrl(string $basename, string $ext)
    {
        return sprintf(
            '%s/%s/%s.%s',
            config('roda.url.image.base'),
            $this->getSaveDirectory($basename),
            $basename,
            $ext
        );
    }

    public function getThumbnailUrl(string $basename, string $ext)
    {
        return  sprintf(
            '%s/%s/%s/%s.%s',
            config('roda.url.image.base'),
            $this->getSaveDirectory($basename),
            config('roda.url.image.thumbnail', 'thumbnail'),
            $basename,
            $ext
        );
    }

    public function getSaveDirectory(string $basename)
    {
        return Str::lower(Str::substr($basename, 0, 1));
    }
}
