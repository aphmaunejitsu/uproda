<?php

namespace Tests\Unit\Repositories\FileRepository;

use Tests\TestCase;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group upload
 * @group FileRepository
 * @group GenerateThumbnailGIFTest
 */
class GenerateThumbnailGifTest extends TestCase
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
     * A basic unit test example.
     *
     * @return void
     */
    public function testGenerateThumbnailGif()
    {
        Storage::fake('image');
        $file = UploadedFile::fake()->image('abc.gif', 500, 500);

        $result = $this->repo->generateThumbnailGif($file, 'xyz', 'gif');
        Storage::disk('image')->assertExists('/x/thumbnail/xyz.gif');
    }
}
