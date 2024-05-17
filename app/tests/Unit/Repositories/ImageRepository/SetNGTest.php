<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;
use App\Models\Image;
use App\Models\Comment;

/**
 * @group Image::SetNG
 * @group ImageRepository
 * @group SetNG
 * @group SetNGTest
 */
class SetNGTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            ImageRepositoryInterface::class,
            ImageRepository::class,
        );

        $this->repo = $this->app->make(ImageRepositoryInterface::class);
    }

    public function testFailedNotFoundImage()
    {
        $image = $this->repo->setNgByBasename('aaaa');
        $this->assertNull($image);
    }

    public function testSetNg()
    {
        Image::factory()
            ->has(Comment::factory()->count(10))
            ->forImageHash(['ng' => 0])
            ->create(['basename' => 'abcde']);

        $image = $this->repo->setNgByBasename('abcde');

        $this->assertTrue($image->imageHash->ng);
    }
}
