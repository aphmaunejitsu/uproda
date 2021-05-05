<?php

namespace Tests\Unit\Services\UploadService;

use App\Exceptions\ImageHashException;
use App\Exceptions\ServiceException;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image;
use App\Models\ImageHash;
use App\Services\Traits\ImageTrait;
use App\Services\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    use ImageTrait;
    use WithFaker;

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

        $result = $this->service->uploadSingleFile($file, ['delkey' => 'test', 'ip' => $this->faker->ipv4]);

        $path = $this->buildImagePath($result->basename, $result->ext);
        $thumb = $this->buildThumbnailPath($result->basename, $result->t_ext);
        $up = FacadesImage::make(Storage::disk('image')->path($path));

        $exif = $up->exif();

        $this->assertInstanceOf(ImageHash::class, $result->imageHash);
        $this->assertInstanceOf(Image::class, $result);
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

    public function testUploadNgFile()
    {
        $this->expectException(ServiceException::class);
        Storage::fake('image');
        Storage::fake('tmp');
        $file = UploadedFile::fake()->image('test.jpg');

        $hash = $this->getHash($file);
        ImageHash::factory()->create([
            'hash' => $hash,
            'ng'   => true
        ]);

        $result = $this->service->uploadSingleFile($file, ['delkey' => 'test']);
    }
}
