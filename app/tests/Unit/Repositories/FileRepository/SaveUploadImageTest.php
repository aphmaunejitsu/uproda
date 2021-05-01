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
 * @group upload
 * @group FileRepository
 * @group SaveUploadImageTest
 */
class SaveUploadImageTest extends TestCase
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

    public function testSuccess()
    {
        Storage::fake('image');
        $file = UploadedFile::fake()->image('abc.png', 200, 300);

        $result = $this->repo->saveUploadImage($file, 'abc', 'png');
        Storage::assertExists('/a/abc.png');
    }
}
