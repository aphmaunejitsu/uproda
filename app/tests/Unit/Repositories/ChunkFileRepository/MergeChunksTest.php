<?php

namespace Tests\Unit\Repositories\ChunkFileRepository;

use App\Models\ChunkFile;
use Tests\TestCase;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Repositories\ChunkFileRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

/**
 * @group upload
 * @group Repository
 * @group ChunkFileRepository
 * @group MergeChunksTest
 */
class MergeChunksTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            ChunkFileRepositoryInterface::class,
            ChunkFileRepository::class
        );

        $this->repo = $this->app->make(ChunkFileRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @dataProvider mergeProvider
     * @return void
     */
    public function testMerge($toStorage)
    {
        $test = Storage::disk('local')->get('test.jpg');

        $size = Storage::disk('local')->size('test.jpg');
        $mimetype = Storage::disk('local')->mimeType('test.jpg');
        $md5  = md5($test);
        Storage::fake('chunk');
        Storage::fake('tmp');
        $bytes = 1024;
        $start = 0;

        $uuid = $this->faker->uuid;

        while (true) {
            $split = substr($test, $start, $bytes);
            if (empty($split)) {
                break;
            }

            $file = UploadedFile::fake()->createWithContent('test', $split);

            $path = $file->store($uuid, 'chunk');
            $this->repo->addChunk($uuid, $start, $path);

            $start += $bytes;
        }

        $result = $this->repo->mergeChunks($uuid, $toStorage);

        Storage::disk($toStorage)->assertExists($uuid);
        $this->assertEquals($uuid, $result['uuid']);
        $this->assertEquals($size, $result['size']);
        $this->assertEquals($mimetype, $result['mimetype']);
        $this->assertEquals($md5, md5_file(Storage::disk($toStorage)->path($uuid)));
    }

    public function mergeProvider()
    {
        return [
            ['chunk'],
            ['tmp']
        ];
    }
}
