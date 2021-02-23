<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\Comment;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group ImageRepository
 * @group CreateTest
 */
class CreateTest extends TestCase
{
    use RefreshDatabase;

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
    public function testCreate()
    {
        $image = Image::factory()->make();

        $result = $this->repo->create($image->toArray());
        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals($image->image_hash_id, $result->image_hash_id);
        $this->assertEquals($image->basename, $result->basename);
        $this->assertEquals($image->ext, $result->ext);
        $this->assertEquals($image->t_ext, $result->t_ext);
        $this->assertEquals($image->original, $result->original);
        $this->assertEquals($image->delkey, $result->delkey);
        $this->assertEquals($image->mimetype, $result->mimetype);
        $this->assertEquals($image->width, $result->width);
        $this->assertEquals($image->height, $result->height);
    }
}
