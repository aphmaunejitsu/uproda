<?php

namespace Tests\Feature\Command;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group console/image/updatesize
 * @group UpdateImageSizeTest
 */
class UpdateImageSizeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotFound()
    {
        $this->artisan('image:updateSize')
             ->expectsOutput('Start Update Image Geometry')
             ->expectsOutput('there is no target')
             ->expectsOutput('Finish Update Image Geometry')
             ->assertExitCode(1);
    }

    public function testUpdateImageSizeWithId()
    {
        Storage::fake('image');
        $file1 = UploadedFile::fake()->image('abc.png', 200, 300);
        $file2 = UploadedFile::fake()->image('xyz.png', 400, 500);
        $file3 = UploadedFile::fake()->image('zzz.png', 400, 500);

        $file1->storeAs('a', 'abc.png', 'image');
        $file2->storeAs('x', 'xyz.png', 'image');
        $file3->storeAs('z', 'zzz.png', 'image');

        $image1 = Image::factory()->create([
            'basename' => 'abc',
            'ext' => 'png'
        ]);
        $image2 = Image::factory()->create([
            'basename' => 'xyz',
            'ext' => 'png'
        ]);
        Image::factory()->create([
            'basename' => 'zzz',
            'ext' => 'png'
        ]);
        $this->artisan('image:updateSize ' . $image1->id . ' ' . $image2->id)
             ->expectsOutput('Start Update Image Geometry')
             ->expectsOutput('Update 2 Images.')
             ->expectsOutput('Finish Update Image Geometry')
             ->assertExitCode(0);
    }

    public function testUpdateImageSizeWithoutId()
    {
        Storage::fake('image');
        $file1 = UploadedFile::fake()->image('abc.png', 200, 300);
        $file2 = UploadedFile::fake()->image('xyz.png', 400, 500);
        $file3 = UploadedFile::fake()->image('zzz.png', 400, 500);

        $file1->storeAs('a', 'abc.png', 'image');
        $file2->storeAs('x', 'xyz.png', 'image');
        $file3->storeAs('z', 'zzz.png', 'image');

        Image::factory()->create([
            'basename' => 'abc',
            'ext' => 'png'
        ]);
        Image::factory()->create([
            'basename' => 'xyz',
            'ext' => 'png'
        ]);
        Image::factory()->create([
            'basename' => 'zzz',
            'ext' => 'png'
        ]);
        $this->artisan('image:updateSize')
             ->expectsOutput('Start Update Image Geometry')
             ->expectsOutput('Update 3 Images.')
             ->expectsOutput('Finish Update Image Geometry')
             ->assertExitCode(0);
    }

    public function testNotUpdateImageSizeWithId()
    {
        Storage::fake('image');
        $file1 = UploadedFile::fake()->image('abc.png', 200, 300);
        $file2 = UploadedFile::fake()->image('xyz.png', 400, 500);
        $file3 = UploadedFile::fake()->image('zzz.png', 400, 500);

        $file1->storeAs('a', 'abc.png', 'image');
        $file2->storeAs('x', 'xyz.png', 'image');
        $file3->storeAs('z', 'zzz.png', 'image');

        Image::factory()->create([
            'basename' => 'abc',
            'ext' => 'png'
        ]);
        Image::factory()->create([
            'basename' => 'xyz',
            'ext' => 'png'
        ]);
        Image::factory()->create([
            'basename' => 'zzz',
            'ext' => 'png'
        ]);
        $this->artisan('image:updateSize 9999 99998')
             ->expectsOutput('Start Update Image Geometry')
             ->expectsOutput('there is no target')
             ->expectsOutput('Finish Update Image Geometry')
             ->assertExitCode(1);
    }
}
