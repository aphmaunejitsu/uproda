<?php

namespace App\Repositories;

interface CommentRepositoryInterface
{
    public function getByImageId(int $image_id, ?int $count);
}
