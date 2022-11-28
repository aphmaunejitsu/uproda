<?php

namespace Tests\Unit\Repositories\FileRepository;

use Tests\TestCase;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;
use App\Services\Contents\Uploading;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group FileRepository
 * @group GetGeometryTest
 */
class GetGeometryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepository::class,
        );

        $this->repo = $this->app->make(FileRepositoryInterface::class);
    }

    /**
     *
     * @return void
     */
    public function testNotFoundException()
    {
        $this->expectException(FileNotFoundException::class);
        $result = $this->repo->getGeometry('abc', 'png');
        $this->assertFalse($result);
    }

    public function testSuccess()
    {
        Storage::fake('image');
        $file = UploadedFile::fake()->image('abc.png', 200, 300);

        $file->storeAs('a', 'abc.png', 'image');

        $result = $this->repo->getGeometry('abc', 'png');
        $this->assertArrayHasKey('width', $result);
        $this->assertArrayHasKey('height', $result);
        $this->assertEquals(200, $result['width']);
        $this->assertEquals(300, $result['height']);
        @unlink($file->getRealPath());
    }
}
