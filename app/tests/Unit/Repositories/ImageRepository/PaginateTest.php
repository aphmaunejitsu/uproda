<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\ImageHash;
use App\Models\Comment;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group api/v1/image/index
 * @group ImageRepository
 * @group PaginateTest
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
            ->has(Comment::factory()->count(10))
            ->count(6)->forImageHash(['ng' => 0 ])->create();
        Image::factory()->count(1)->forImageHash(['ng' => 1 ])->create();
        $result = $this->repo->paginate(5);

        $this->assertEquals(6, $result->total());
        $this->assertEquals(5, $result->perPage());
        $this->assertTrue($result->hasMorePages());
        $this->assertInstanceOf(Image::class, $result[0]);
        $this->assertInstanceOf(ImageHash::class, $result[0]->imageHash);
        $this->assertEquals(10, $result[0]->comments_count);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPaginateNg()
    {
        Image::factory()->count(1)->forImageHash(['ng' => 1 ])->create();
        $result = $this->repo->paginate();


        $this->assertEquals(0, $result->total());
        $this->assertFalse($result->hasMorePages());
    }
}
