<?php

namespace Tests\Unit\Services\UploadService;

use App\Services\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

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

    public function testChunkedUpload()
    {
        Storage::fake('chunk');
        $file = UploadedFile::fake()->image('test.jpg');

        $uuid = $this->faker->uuid;
        $cr = [
            'start' => 0,
            'end'   => $file->getSize() - 1,
            'size'  => $file->getSize(),
        ];

        $result = $this->service->chunkedUpload($file, compact('uuid'), $cr);
        Storage::disk('chunk')->assertExists($result['path']);
        Redis::del($uuid);
    }
}
