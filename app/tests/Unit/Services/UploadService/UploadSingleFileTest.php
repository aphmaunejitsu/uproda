<?php

namespace Tests\Unit\Services\UploadService;

use App\Libs\Traits\BuildImagePath;
use App\Models\Image;
use App\Models\ImageHash;
use App\Services\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as FacadesImage;
use Tests\TestCase;

/**
 * @group Service
 * @group upload
 * @group UploadService
 * @group UploadSingleFileTest
 *
 */
class UploadSingleFileTest extends TestCase
{
    use RefreshDatabase;
    use BuildImagePath;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new UploadService();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUploadSingleFile()
    {
        $test = Storage::disk('local')->path('test.jpg');
        Storage::fake('image');
        Storage::fake('tmp');
        $image = FacadesImage::make($test);
        $file = UploadedFile::fake()->createWithContent('lua.jpg', $image->stream());

        $old = $image->exif();

        $result = $this->service->uploadSingleFile($file, ['delkey' => 'test']);

        $im = $result->images[0];

        $path = $this->buildImagePath($im->basename, $im->ext);
        $thumb = $this->buildThumbnailPath($im->basename, $im->t_ext);
        $up = FacadesImage::make(Storage::disk('image')->path($path));

        $exif = $up->exif();

        $this->assertInstanceOf(ImageHash::class, $result);
        $this->assertInstanceOf(Image::class, $result->images[0]);
        Storage::disk('image')->assertExists($path);
        Storage::disk('image')->assertExists($thumb);
        $this->assertEquals(0, $exif['Orientation']);
        $this->assertNotEquals($old['GPSLatitude'][0], $exif['GPSLatitude'][0]);
        $this->assertNotEquals($old['GPSLatitude'][1], $exif['GPSLatitude'][1]);
        $this->assertNotEquals($old['GPSLatitude'][2], $exif['GPSLatitude'][2]);
        $this->assertNotEquals($old['GPSLongitude'][0], $exif['GPSLongitude'][0]);
        $this->assertNotEquals($old['GPSLongitude'][1], $exif['GPSLongitude'][1]);
        $this->assertNotEquals($old['GPSLongitude'][2], $exif['GPSLongitude'][2]);
    }
}
