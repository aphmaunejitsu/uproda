<?php

namespace Tests\Unit\Repositories\FileRepository;

use Tests\TestCase;
use App\Repositories\FileRepositoryInterface;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * @group upload
 * @group FileRepository
 * @group ChangeLocationTest
 */
class ChangeLocationTest extends TestCase
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
    public function testChangeLocation()
    {
        $test = Storage::disk('local')->path('test.jpg');
        Storage::fake('tmp');
        $image = Image::make($test);
        Storage::disk('tmp')->put('gps.jpg', $image->stream());
        $path = Storage::disk('tmp')->path('gps.jpg');

        $latitude  = 35.630152;
        $longitude = 139.74044000000004;
        $altitude  = 11.0;

        $old = $image->exif();

        $this->repo->changeLocation(
            $path,
            $latitude,
            $longitude,
            $altitude
        );

        $image = Image::make($path);
        $exif = $image->exif();

        $this->assertNotEquals($old['GPSLatitude'][0], $exif['GPSLatitude'][0]);
        $this->assertNotEquals($old['GPSLatitude'][1], $exif['GPSLatitude'][1]);
        $this->assertNotEquals($old['GPSLatitude'][2], $exif['GPSLatitude'][2]);
        $this->assertNotEquals($old['GPSLongitude'][0], $exif['GPSLongitude'][0]);
        $this->assertNotEquals($old['GPSLongitude'][1], $exif['GPSLongitude'][1]);
        $this->assertNotEquals($old['GPSLongitude'][2], $exif['GPSLongitude'][2]);

        @unlink($path);
    }
}
