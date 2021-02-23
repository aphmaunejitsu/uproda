<?php

namespace App\Repositories;

interface ImageRepositoryInterface
{
    public function paginate(int $perPage);
    public function saveComment(int $id, string $comment);
}
