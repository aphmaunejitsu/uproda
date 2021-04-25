<?php

namespace Tests\Unit\Services\FileService;

use App\Models\Image;
use App\Repositories\FileRepositoryInterface;
use App\Services\FileService;
use Tests\TestCase;
use Mockery\MockInterface;

/**
 * @group api/v1/image/delete
 * @group FileService
 * @group DeleteFileTest
 */
class DeleteFileTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDeleteFile()
    {
        $this->mock(
            FileRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('deleteByImage')->andReturn(true);
            }
        );

        $image = Image::factory()->make();
        $result = (new FileService())->deleteFile($image);

        $this->assertTrue($result);
    }
}
