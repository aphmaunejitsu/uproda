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
 * @group UpdateGeometryTest
 */
class UpdateGeometryTest extends TestCase
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

    public function testUpdateGeometry()
    {
        $image = Image::factory()->create();
        $result = $this->repo->updateGeometry($image->id, 100, 300);
        $this->assertEquals(100, $result->width);
        $this->assertEquals(300, $result->height);
    }

    public function testFailUpdate()
    {
        $result = $this->repo->updateGeometry(0, 100, 300);
        $this->assertNull($result);
    }
}
