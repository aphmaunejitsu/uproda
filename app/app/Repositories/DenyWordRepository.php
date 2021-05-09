<?php

namespace App\Repositories;

use App\Models\DenyWord;

class DenyWordRepository implements DenyWordRepositoryInterface
{
    public $model;

    public function __construct(DenyWord $model)
    {
        $this->model = $model;
    }

    public function get()
    {
        return $this->model->get();
    }
}
