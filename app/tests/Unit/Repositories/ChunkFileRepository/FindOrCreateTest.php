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
 * @group ChunkFileTest
 * @group FindOrCreateTest
 */
class FindOrCreateTest extends TestCase
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
    public function testCreateSuccess()
    {
        $data = [
            'uuid'        => $this->faker->uuid,
            'is_uploaded' => true,
            'is_fail'     => false,
        ];
        $result = $this->repo->findOrCreate($data);
        $this->assertInstanceOf(ChunkFile::class, $result);
        $this->assertEquals($data['uuid'], $result->uuid);
        $this->assertEquals($data['is_uploaded'], $result->is_uploaded);
        $this->assertEquals($data['is_fail'], $result->is_fail);
    }

    public function testFindSuccess()
    {
        $create = ChunkFile::factory()->create([
            'uuid'        => $this->faker->uuid,
            'is_uploaded' => true,
            'is_fail'     => false,
        ]);
        $data = [
            'uuid'        => $create->uuid,
            'is_uploaded' => false,
            'is_fail'     => true,
        ];
        $result = $this->repo->findOrCreate($data);
        $this->assertInstanceOf(ChunkFile::class, $result);
        $this->assertEquals($data['uuid'], $result->uuid);
        $this->assertEquals($result->is_uploaded, $create->is_uploaded);
        $this->assertEquals($result->is_fail, $create->is_fail);
    }
}
