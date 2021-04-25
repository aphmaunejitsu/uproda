<?php

namespace Tests\Unit\Repositories\FileRepository;

use App\Libs\Traits\BuildImagePath;
use App\Models\Image;
use Tests\TestCase;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group FileRepository
 * @group DeleteByImageTest
 */
class DeleteByImageTest extends TestCase
{
    use BuildImagePath;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            FileRepositoryInterface::class,
            FileRepository::class,
        );

        $this->repo = $this->app->make(FileRepositoryInterface::class);
    }
    public function testNotFound()
    {
        $image = Image::factory()->make([
            'basename' => 'xyz',
            'ext'      => 'png',
            't_ext'    => 'jpg',
        ]);
        $result = $this->repo->deleteByImage($image);
        $this->assertFalse($result);
    }

    public function testDelete()
    {
        $image = Image::factory()->make([
            'basename' => 'xyz',
            'ext'      => 'png',
            't_ext'    => 'jpg',
        ]);

        $imageName = $this->buildFilename($image->basename, $image->ext);
        $imageDir  = $this->getSaveDirectory($image->basename);
        $thumbName  = $this->buildFilename($image->basename, $image->t_ext);
        $thumbDir  = $this->buildThumbnailDir($image->basename);

        Storage::fake('image');
        $file = UploadedFile::fake()->image($imageName, 600, 480);
        $file->storeAs($imageDir, $imageName, 'image');
        $thumb = UploadedFile::fake()->image($thumbName, 400, 400);
        $thumb->storeAs($thumbDir, $thumbName, 'image');

        Storage::disk('image')->assertExists($this->buildImagePath($image->basename, $image->ext));
        Storage::disk('image')->assertExists($this->buildThumbnailPath($image->basename, $image->t_ext));

        $result = $this->repo->deleteByImage($image);

        Storage::disk('image')->assertMissing($this->buildImagePath($image->basename, $image->ext));
        Storage::disk('image')->assertMissing($this->buildThumbnailPath($image->basename, $image->t_ext));
        $this->assertTrue($result);
    }
}
