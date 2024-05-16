<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\Comment;

class ImageRepository implements ImageRepositoryInterface
{
    public $model;
    public function __construct(Image $image)
    {
        $this->model = $image;
    }

    public function findByBasename(string $basename)
    {
        return $this->model
                    ->whereHas('imageHash', function ($query) {
                        $query->where('ng', 0);
                    })
                    ->with([
                        'imageHash',
                        'comments' => function ($query) {
                            $query->orderby('created_at', 'desc');
                        }
                    ])
                    ->withCount('comments')
                    ->where('basename', $basename)
                    ->first();
    }

    public function paginate(int $perPage = 50)
    {
        return $this->model
                    ->whereHas('imageHash', function ($query) {
                        $query->where('ng', 0);
                    })
                    ->with('imageHash')
                    ->withCount('comments')
                    ->orderby('created_at', 'desc')
                    ->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateThumbnailExt(int $id, string $ext)
    {
        if (!($image = $this->model->find($id))) {
            return null;
        }

        $image->t_ext = $ext;
        $image->save();
        return $image;
    }

    public function saveComment(int $id, string $comment)
    {
        if (! ($image = $this->model->find($id))) {
            return null;
        }

        return $image->comments()
                     ->save(
                         new Comment(['comment' => $comment])
                     );
    }

    public function getByIds(?array $ids = null)
    {
        return $this->model
                    ->when($ids, function ($query, $ids) {
                        $query->find($ids);
                    })
                    ->orderby('created_at')
                    ->get();
    }

    public function updateGeometry(int $id, int $width, int $height)
    {
        if (!($image = $this->model->find($id))) {
            return null;
        }

        $image->width = $width;
        $image->height = $height;

        $image->save();

        return $image;
    }

    public function deleteByBasename(string $basename, string $password)
    {
        if (!($image = $this->model->where('basename', $basename)->first())) {
            return null;
        }

        if ($password !== config('roda.delkey')) {
            if ($password !== $image->delkey) {
                return null;
            }
        }

        $image->delete();
        return $image;
    }

    public function setNgByBasename(string $basename)
    {
        if (!($image = $this->model->where('basename', $basename)->first())) {
            return null;
        }

        $image->imageHash->hg = 1;
        $image->imageHash()->save();
        return $image;
    }

}
