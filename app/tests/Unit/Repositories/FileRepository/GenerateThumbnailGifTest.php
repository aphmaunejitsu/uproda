<?php

namespace Tests\Unit\Repositories\FileRepository;

use App\Exceptions\FileRepositoryException;
use Tests\TestCase;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

        $image = Image::make(Storage::disk('image')->readStream('/x/thumbnail/xyz.gif'));
        Storage::disk('image')->assertExists('/x/thumbnail/xyz.gif');
        $this->assertEquals(config('roda.thumbnail.height'), $image->height());
        $this->assertEquals(config('roda.thumbnail.width'), $image->width());
    }

    public function testException()
    {
        $this->expectException(FileRepositoryException::class);
        $file = UploadedFile::fake()->image('abc.jpg', 500, 500);
        $result = $this->repo->generateThumbnailGif($file, 'xyz', 'gif');
    }
}
