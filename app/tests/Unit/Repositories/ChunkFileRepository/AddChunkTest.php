<?php

namespace Tests\Unit\Repositories\ChunkFileRepository;

use Tests\TestCase;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Repositories\ChunkFileRepository;
use Illuminate\Support\Facades\Redis;

/**
 * @group upload
 * @group Repository
 * @group ChunkFileRepository
 * @group AddChunkTest
 */
class AddChunkTest extends TestCase
{
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
    public function testChunk()
    {
        $result = $this->repo->addChunk('aaa', 100, 'bbb');
        $score = Redis::zscore('aaa', 'bbb');
        $this->assertEquals(1, $result);
        $this->assertEquals(100, $score);
        Redis::del('aaa');
    }
}
