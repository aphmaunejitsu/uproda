<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;

/**
 * @group ImageRepositoryPaginateTest
 */
class PaginateTest extends TestCase
{
    use RefreshDatabase;

    public $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            \App\Repositories\ImageRepositoryInterface::class,
            \App\Repositories\ImageRepository::class,
        );

        $this->repo = $this->app->make(\App\Repositories\ImageRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testNotFound()
    {
        $result = $this->repo->paginate();

        $this->assertCount(0, $result);
        $this->assertEquals(0, $result->total());
        $this->assertFalse($result->hasMorePages());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPaginate()
    {
        Image::factory()->count(51)->create();
        $result = $this->repo->paginate(10);

        $this->assertEquals(51, $result->total());
        $this->assertEquals(10, $result->perPage());
        $this->assertTrue($result->hasMorePages());
    }
}
