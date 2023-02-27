<?php

namespace App\Libs\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait BuildImagePath
{
    public function getImageUrl(string $basename, string $ext)
    {
        $storage = $this->getImageStorage();
        return Storage::disk($storage)->url(
            $this->buildImagePath($basename, $ext)
        );
    }

    public function getThumbnailUrl(string $basename, string $ext)
    {
        $storage = $this->getImageStorage();
        $path = $this->buildThumbnailPath($basename, $ext);
        if (Storage::disk($storage)->exists($path)) {
            return Storage::disk($storage)->url($path);
        } else {
            return url('/images/noimage.png');
        }
    }

    public function getImageStorage()
    {
        return config('roda.storage', 'image');
    }

    public function buildImagePath(string $basename, string $ext)
    {
        return sprintf(
            '/%s/%s',
            $this->getSaveDirectory($basename),
            $this->buildFilename($basename, $ext)
        );
    }

    public function buildThumbnailPath(string $basename, string $ext)
    {
        return sprintf(
            '%s/%s',
            $this->buildThumbnailDir($basename),
            $this->buildFilename($basename, $ext)
        );
    }

    public function buildThumbnailDir(string $basename)
    {
        return sprintf(
            '/%s/%s',
            $this->getSaveDirectory($basename),
            config('roda.url.image.thumbnail', 'thumbnail')
        );
    }

    public function buildFilename(string $basename, string $ext)
    {
        return sprintf('%s.%s', $basename, $ext);
    }

    public function getSaveDirectory(string $basename)
    {
        return Str::lower(Str::substr($basename, 0, 1));
    }
}
