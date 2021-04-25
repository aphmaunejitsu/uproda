<?php

namespace Tests\Feature\Api\V1\Image;

use App\Jobs\Image\ProcessDelete;
use App\Libs\Traits\BuildImagePath;
use App\Models\Image;
use App\Services\FileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * @group api/v1/image/delete
 * @group DeleteTest
 */
class DeleteTest extends TestCase
{
    use RefreshDatabase;
    use BuildImagePath;

    public function setUp(): void
    {
        parent::setUp();
    }


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDelete()
    {
        $image = Image::factory()->create([
            'basename' => 'xxxxxx',
            'ext'      => 'png',
            't_ext'    => 'jpg',
            'delkey'   => 'xyzabc'
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

        Bus::fake();

        $url = route('v1.image.delete');
        $response = $this->deleteJson(
            $url,
            [
                'basename' => $image->basename,
                'delkey'   => $image->delkey
            ]
        );

        Bus::assertDispatched(function (ProcessDelete $job) use ($image) {
            return true;
        });

        $response->assertStatus(204);
    }
}
