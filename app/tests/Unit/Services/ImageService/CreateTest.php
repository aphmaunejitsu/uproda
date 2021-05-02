<?php

namespace Tests\Unit\Services\ImageService;

use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\ImageService;
use Mockery\MockInterface;

/**
 * @group api/v1/image/create
 * @group ImageService
 * @group CreateTest
 */
class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreate()
    {
        $data = Image::factory()->make();
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('create')->andReturn(
                    Image::factory()->make()
                );
            }
        );

        $result = (new ImageService())->create($data);
        $this->assertInstanceOf(Image::class, $result);
    }
}
