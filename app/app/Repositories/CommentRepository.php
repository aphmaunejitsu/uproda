<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public $model;
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    public function getByImageId(int $image_id, ?int $count = null)
    {
        return $this->model
                    ->where('image_id', $image_id)
                    ->when($count, function ($query, $count) {
                        return $query->limit($count);
                    })
                    ->orderby('created_at', 'desc')
                    ->get();
    }
}
