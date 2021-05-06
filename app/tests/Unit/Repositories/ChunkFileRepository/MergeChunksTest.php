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
     * @return void
     */
    public function testMerge()
    {
        $test = Storage::disk('local')->get('test.jpg');

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

        $result = $this->repo->mergeChunks($uuid);

        $this->assertTrue($result);
        Storage::disk('chunk')->assertExists($uuid);
    }
}
