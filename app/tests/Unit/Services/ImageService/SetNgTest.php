<?php

namespace Tests\Unit\Services\ImageService;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\ImageService;
use App\Exceptions\ImageServiceException;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image;
use App\Models\ImageHash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group Image::SetNG
 * @group ImageService
 * @group SetNgTest
 */
class SetNgTest extends TestCase
{
    use RefreshDatabase;
    use BuildImagePath;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic unit test example.
     */
    public function testSetNgNotFound()
    {
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('setNgByBasename')->andReturn(null);
            }
        );

        $this->expectException(ImageServiceException::class);
        $this->expectExceptionCode(10000);
        $this->expectExceptionMessage('NGに設定できませんでした');

        (new ImageService)->setNg('aaa');
    }

    public function testFailedSetNg()
    {
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $image = Image::factory()->make(['basename' => 'test']);
                $image->imageHash->ng = 0;
                $m->shouldReceive('setNgByBasename')->andReturn($image);
            }
        );

        $this->expectException(ImageServiceException::class);
        $this->expectExceptionCode(10001);
        $this->expectExceptionMessage('NGに設定できませんでした');

        (new ImageService)->setNg('test');
    }

    public function testSuccess()
    {
        $image = Image::factory()->create([
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

        $result = (new ImageService())->setNg('xyz');

        $this->assertTrue($result);
        Storage::disk('image')->assertMissing($this->buildImagePath($image->basename, $image->ext));
        Storage::disk('image')->assertMissing($this->buildThumbnailPath($image->basename, $image->t_ext));
    }
}
