<?php

namespace Tests\Unit\Services\ImageService;

use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\ImageService;
use Mockery\MockInterface;

/**
 * @group api/v1/image/delete
 * @group ImageService
 * @group DeleteImageTest
 */
class DeleteImageTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     *
     * @return void
     */
    public function testNotDelete()
    {
        $result = (new ImageService())->deleteImage('test', 'abc');
        $this->assertNull($result);
    }

    public function testDelete()
    {
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('deleteByBasename')->andReturn(
                    Image::factory()->make()
                );
            }
        );

        $result = (new ImageService())->deleteImage('test', 'abc');
        $this->assertInstanceOf(Image::class, $result);
    }
}
