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
 * @group GenerateThumbnailTest
 */
class GenerateThumbnailTest extends TestCase
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
    public function testGenerateThumbnail()
    {
        Storage::fake('image');
        $file = UploadedFile::fake()->image('abc.png', 500, 500);

        $result = $this->repo->generateThumbnail(
            $file->getRealPath(),
            'xyz'
        );

        $image = Image::make(Storage::disk('image')->readStream('/x/thumbnail/xyz.jpg'));
        Storage::disk('image')->assertExists('/x/thumbnail/xyz.jpg');
        $this->assertEquals(config('roda.thumbnail.height'), $image->height());
        $this->assertEquals(config('roda.thumbnail.width'), $image->width());
    }

    public function testException()
    {
        $this->expectException(FileRepositoryException::class);
        Storage::fake('tmp');
        $im = imagecreatetruecolor(500, 600);
        $path = Storage::disk('tmp')->path('test.gif');
        imagegif($im, $path);
        $file = UploadedFile::fake()->createWithContent('gif.gif', file_get_contents($path));
        $result = $this->repo->generateThumbnail(
            $file->getRealPath(),
            'xyz'
        );
    }
}
