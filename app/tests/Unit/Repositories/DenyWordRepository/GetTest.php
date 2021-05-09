<?php

namespace Tests\Unit\Repositories\DenyWordRepository;

use App\Models\DenyWord;
use App\Repositories\DenyWordRepository;
use App\Repositories\DenyWordRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group Repository
 * @group DenyWordRepository
 * @group GetTest
 */
class GetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            DenyWordRepositoryInterface::class,
            DenyWordRepository::class
        );

        $this->repo = $this->app->make(DenyWordRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGet()
    {
        DenyWord::factory()->count(10)->create();
        $result = $this->repo->get();
        $this->assertCount(10, $result);
    }
}
