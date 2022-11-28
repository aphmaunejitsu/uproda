<?php

namespace Tests\Unit\Repositories\ChunkFileRepository;

use App\Models\ChunkFile;
use Tests\TestCase;
use App\Repositories\ChunkFileRepositoryInterface;
use App\Repositories\ChunkFileRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;

/**
 * @group upload
 * @group Repository
 * @group ChunkFileRepository
 * @group ChunkFileTEst
 */
class ChunkFileTest extends TestCase
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
    public function testChunk()
    {
        $result = $this->repo->addChunk('aaa', 100, 'bbb');
        $score = Redis::zscore('aaa', 'bbb');
        $this->assertEquals(1, $result);
        $this->assertEquals(100, $score);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetChunk()
    {
        $this->repo->addChunk('test', 300, 'bbb');
        $this->repo->addChunk('test', 100, 'ccc');
        $this->repo->addChunk('test', 200, 'aaa');
        $scores = $this->repo->getChunks('test');
        $this->assertCount(3, $scores);
        $this->assertEquals('ccc', $scores[0]);
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
        $this->assertEquals(3, $result);
    }

    public function testGetByUuid()
    {
        ChunkFile::factory()->create([
            'uuid' => 'abc'
        ]);

        $result = $this->repo->getByUuid('abc');
        $this->assertEquals('abc', $result->uuid);
    }

    public function testCreate()
    {
        $uuid = $this->faker->uuid;
        $result = $this->repo->createByUuid($uuid);
        $this->assertEquals($uuid, $result->uuid);
    }
}
