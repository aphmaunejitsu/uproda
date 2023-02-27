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
                $m->shouldReceive('findByBasename')->andReturn(
                    Image::factory()->make([
                         'basename' => 'test',
                         'delkey'   => 'abc',
                    ])
                );
            }
        );

        $this->mock(Image::class, function ($mock) {
            $mock->shouldReceive('delete')
                 ->andReturnTrue();
        });

        $result = (new ImageService())->deleteImage('test', 'abc');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals($result->delkey, 'abc');
    }

    public function testDeleteByDefaultDelkey()
    {
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('findByBasename')->andReturn(
                    Image::factory()->make([
                         'basename' => 'test',
                         'delkey'   => 'xyz',
                    ])
                );
            }
        );

        $this->mock(Image::class, function ($mock) {
            $mock->shouldReceive('delete')
                 ->andReturnTrue();
        });

        $result = (new ImageService())->deleteImage('test', 'example');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertNotEquals($result->delkey, 'example');
    }

    public function testNotFound()
    {
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('findByBasename')->andReturnFalse();
            }
        );

        $result = (new ImageService())->deleteImage('test', 'abc');
        $this->assertEquals($result, null);
    }
}
