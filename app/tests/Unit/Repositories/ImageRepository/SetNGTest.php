<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group api/v1/image/delete
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
}
