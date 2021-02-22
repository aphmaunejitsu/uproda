<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\ImageHash;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @group api/v1/image/index
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
            ImageRepositoryInterface::class,
            ImageRepository::class,
        );

        $this->repo = $this->app->make(ImageRepositoryInterface::class);
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
        Image::factory()
            ->count(51)
            ->forImageHash(['ng' => 0 ])->create();
        Image::factory()
            ->count(51)
            ->forImageHash(['ng' => 1 ])->create();
        $result = $this->repo->paginate(50);


        $this->assertEquals(51, $result->total());
        $this->assertEquals(50, $result->perPage());
        $this->assertTrue($result->hasMorePages());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPaginateNg()
    {
        Image::factory()
            ->count(51)
            ->forImageHash(['ng' => 1 ])->create();
        $result = $this->repo->paginate(50);


        $this->assertEquals(0, $result->total());
        $this->assertFalse($result->hasMorePages());
    }
}
