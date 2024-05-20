<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\ImageHash;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group Image::SetNG
 * @group ImageRepository
 * @group DeleteImage
 * @group DeleteByImageHashTest
 */
class DeleteByImageHashTest extends TestCase
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
    public function testFailDelete()
    {
        $image = $this->repo->deleteByImageHash('abc');
        $this->assertEquals(0, $image->count());
    }

    public function testDelete()
    {
        $ih = ImageHash::factory()
            ->has(Image::factory()->count(5))
            ->create(['ng' => 1, 'hash' => 'abc']);

        $result = $this->repo->deleteByImageHash($ih->hash);
        $this->assertEquals(5, $result->count());
    }
}
