<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group console/image/updatesize
 * @group ImageRepository
 * @group GetByIdsTest
 */
class GetByIdsTest extends TestCase
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
    public function testGetByIdsWithIds()
    {
        $images = Image::factory()->count(11)->create();
        $pluck = $images->pluck('id');
        $ids = $pluck->slice(1, 4);
        $result = $this->repo->getByIds($ids->toArray());
        $this->assertEquals(4, $result->count());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetByIdsWithoutIds()
    {
        $images = Image::factory()->count(11)->create();
        $result = $this->repo->getByIds();
        $this->assertEquals(11, $result->count());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testNotFound()
    {
        $result = $this->repo->getByIds();
        $this->assertEquals(0, $result->count());
    }
}
