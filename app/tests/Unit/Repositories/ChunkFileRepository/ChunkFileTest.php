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
 * @group ChunkFileTEst
 */
class ChunkFileTest extends TestCase
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

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetChunk()
    {
        $result = $this->repo->addChunk('test', 0, 'bbb');
        $result = $this->repo->addChunk('test', 100, 'ccc');
        $result = $this->repo->addChunk('test', 200, 'aaa');
        $scores = $this->repo->getChunks('test');
        $this->assertCount(3, $scores);
        Redis::del('test');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testRemove()
    {
        $this->repo->addChunk('test-del', 0, 'bbb');
        $this->repo->addChunk('test-del', 100, 'ccc');
        $this->repo->addChunk('test-del', 200, 'aaa');
        $result = $this->repo->remove('test-del');
        $this->assertEquals(1, $result);
    }
}
