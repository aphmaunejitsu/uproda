<?php

namespace Tests\Feature\Repositories;

use App\Models\DenyWord;
use App\Repositories\DenyWordRepository;
use App\Repositories\DenyWordRepositoryInterface;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class DenyWordRepositoryTest extends TestCase
{
    use RefreshTestDatabase;

    private DenyWordRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(DenyWordRepositoryInterface::class, DenyWordRepository::class);
        $this->repo = $this->app->make(DenyWordRepositoryInterface::class);
    }

    public function testGet(): void
    {
        DenyWord::factory()->count(5)->create();

        $result = $this->repo->get();

        $this->assertCount(5, $result);
    }
}
