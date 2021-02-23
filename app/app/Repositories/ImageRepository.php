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
                    ->orderby('created_at')->paginate($perPage);
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
}
