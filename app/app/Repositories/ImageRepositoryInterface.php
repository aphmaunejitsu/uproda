<?php

namespace App\Repositories;

interface ImageRepositoryInterface
{
    public function findByBasename(string $basename);
    public function paginate(int $perPage);
    public function create(array $data);
    public function saveComment(int $id, string $comment);
    public function updateGeometry(int $id, int $width, int $height);
    public function updateThumbnailExt(int $id, string $ext);

    public function getByIds(?array $ids);
    public function deleteByBasename(string $basename, string $password);
    public function deleteByImageHash(string $hash);
    public function setNgByBasename(string $basename);
}
