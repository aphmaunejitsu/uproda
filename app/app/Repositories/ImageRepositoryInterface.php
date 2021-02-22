<?php

namespace App\Repositories;

interface ImageRepositoryInterface
{
    public function paginate(int $perPage);
}
