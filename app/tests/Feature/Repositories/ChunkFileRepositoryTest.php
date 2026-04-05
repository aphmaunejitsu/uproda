<?php

namespace Tests\Feature\Repositories;

use App\Models\ChunkFile;
use App\Repositories\ChunkFileRepository;
use App\Repositories\ChunkFileRepositoryInterface;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class ChunkFileRepositoryTest extends TestCase
{
    use RefreshTestDatabase;

    private ChunkFileRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(ChunkFileRepositoryInterface::class, ChunkFileRepository::class);
        $this->repo = $this->app->make(ChunkFileRepositoryInterface::class);
    }

    public function testFindOrCreateNew(): void
    {
        $data = [
            'uuid'        => 'test-uuid-1234',
            'is_uploaded' => false,
            'is_fail'     => false,
        ];

        $result = $this->repo->findOrCreate($data);

        $this->assertNotNull($result);
        $this->assertEquals('test-uuid-1234', $result->uuid);
        $this->assertDatabaseHas('chunk_files', ['uuid' => 'test-uuid-1234']);
    }

    public function testFindOrCreateExisting(): void
    {
        $existing = ChunkFile::factory()->create(['uuid' => 'existing-uuid']);

        $result = $this->repo->findOrCreate([
            'uuid'        => 'existing-uuid',
            'is_uploaded' => true,
            'is_fail'     => true,
        ]);

        $this->assertEquals($existing->id, $result->id);
        $this->assertEquals(1, ChunkFile::where('uuid', 'existing-uuid')->count());
    }

    public function testGetByUuid(): void
    {
        ChunkFile::factory()->create(['uuid' => 'lookup-uuid']);

        $result = $this->repo->getByUuid('lookup-uuid');

        $this->assertNotNull($result);
        $this->assertEquals('lookup-uuid', $result->uuid);
    }

    public function testGetByUuidNotFound(): void
    {
        $result = $this->repo->getByUuid('nonexistent-uuid');

        $this->assertNull($result);
    }
}
