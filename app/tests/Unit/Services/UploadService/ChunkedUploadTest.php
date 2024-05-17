<?php

namespace Tests\Unit\Services\UploadService;

use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Services\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery\MockInterface;

/**
 * @group upload
 * @group Service
 * @group UploadService
 * @group ChunkedUploadTest
 */
class ChunkedUploadTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UploadService();
    }

    /**
     * @dataProvider chunkNullProvider
     */
    public function testChunkNull(array $cr)
    {
        $this->mock(
            ChunkFileRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('createByUuid')->andReturn(null);
                $m->shouldReceive('getByUuid')->andReturn(null);
            }
        );

        $file = UploadedFile::fake()->image('test.jpg');

        $uuid = $this->faker->uuid;

        $result = $this->service->chunkedUpload($file, compact('uuid'), $cr);

        $this->assertNull($result);
    }

    public static function chunkNullProvider()
    {
        return [
            [
                ['is_first' => true]
            ],
            [
                ['is_first' => false]
            ],
        ];
    }

    public function testChunkedUploadIsFirst()
    {
        Storage::fake('chunk');
        $file = UploadedFile::fake()->image('test.jpg');

        $uuid = $this->faker->uuid;
        $cr = [
            'start'    => 0,
            'end'      => $file->getSize() - 1,
            'size'     => $file->getSize(),
            'is_first' => true,
            'is_last'  => false
        ];

        $result = $this->service->chunkedUpload($file, compact('uuid'), $cr);
        $r = Redis::zrange($uuid, 0, -1);

        Storage::disk('chunk')->assertExists($r[0]);
        $this->assertEquals($uuid, $result['uuid']);
        $this->assertNull($result['path']);
    }

    public function testChunkedUploadIsNotFirstThenFailMerge()
    {
        $uuid = $this->faker->uuid;
        $this->mock(
            ChunkFileRepositoryInterface::class,
            function (MockInterface $m) use ($uuid) {
                $m->shouldReceive('getByUuid')->andReturn(
                    ChunkFile::factory()->make([
                        'uuid' => $uuid,
                    ])
                );
                $m->shouldReceive('addChunk')->andReturn(true);
                $m->shouldReceive('mergeChunks')->andReturn(null);
            }
        );
        Storage::fake('chunk');
        $file = UploadedFile::fake()->image('test.jpg');

        $cr = [
            'start'    => 0,
            'end'      => $file->getSize() - 1,
            'size'     => $file->getSize(),
            'is_first' => false,
            'is_last'  => true
        ];

        $result = $this->service->chunkedUpload($file, compact('uuid'), $cr);

        $this->assertNull($result);
    }

    public function testChunkedUploadIsLast()
    {
        $uuid = $this->faker->uuid;
        $this->mock(
            ChunkFileRepositoryInterface::class,
            function (MockInterface $m) use ($uuid) {
                $m->shouldReceive('getByUuid')->andReturn(
                    ChunkFile::factory()->make([
                        'uuid' => $uuid,
                    ])
                );
                $m->shouldReceive('addChunk')->andReturn(true);
                $m->shouldReceive('mergeChunks')->andReturn([
                    'size' => 100,
                    'path' => 'test'
                ]);
            }
        );
        Storage::fake('chunk');
        $file = UploadedFile::fake()->image('test.jpg');

        $cr = [
            'start'    => 0,
            'end'      => $file->getSize() - 1,
            'size'     => $file->getSize(),
            'is_first' => false,
            'is_last'  => true
        ];

        $result = $this->service->chunkedUpload($file, compact('uuid'), $cr);

        $this->assertNotEmpty($result);
    }

}
