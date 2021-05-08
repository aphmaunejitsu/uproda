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
 * @group OrientateTest
 */
class OrientateTest extends TestCase
{
    private $repo;
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
    public function testOrientateTest()
    {
        $test = Storage::disk('local')->path('test.jpg');
        Storage::fake('tmp');
        $image = Image::make($test);
        Storage::disk('tmp')->put('gps.jpg', $image->stream());
        $path = Storage::disk('tmp')->path('gps.jpg');

        $original = Image::make($test)->exif();
        $this->repo->orientate($path);

        $changed = Image::make($path)->exif();

        $this->assertNotEquals($changed['Orientation'], $original['Orientation']);
        Storage::disk('tmp')->assertExists('gps.jpg');
        @unlink($path);
    }
}
