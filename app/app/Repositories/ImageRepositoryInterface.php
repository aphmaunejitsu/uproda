<?php

namespace App\Repositories;

interface ImageRepositoryInterface
{
    public function findByBasename(string $basename);
    public function paginate(int $perPage);
    public function create(array $data);
    public function saveComment(int $id, string $comment);
}
