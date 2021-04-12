<?php

namespace Tests\Unit\Services\ImageService;

use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @group console/image/updatesize
 * @group ImageService
 * @group UpdateSizeTest
 */
class UpdateSizeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     *
     * @return void
     */
    public function testNotFoundWithoutIds()
    {
        $result = (new ImageService())->updateSize();
        $this->assertEmpty($result);
    }

    /**
     *
     * @return void
     */
    public function testNotFoundWithIds()
    {
        $result = (new ImageService())->updateSize([1, 2, 3]);
        $this->assertEmpty($result);
    }

    public function testUpdateSize()
    {
        Storage::fake('image');
        $file1 = UploadedFile::fake()->image('abc.png', 200, 300);
        $file2 = UploadedFile::fake()->image('xyz.png', 400, 500);

        $file1->storeAs('a', 'abc.png', 'image');
        $file2->storeAs('x', 'xyz.png', 'image');

        $image1 = Image::factory()->create([
            'basename' => 'abc',
            'ext' => 'png'
        ]);
        $image2 = Image::factory()->create([
            'basename' => 'xyz',
            'ext' => 'png'
        ]);

        $result = (new ImageService())->updateSize([$image1->id, $image2->id]);
        $this->assertCount(2, $result);
        $this->assertEquals(200, $result[0]['width']);
        $this->assertEquals(300, $result[0]['height']);
        $this->assertEquals(400, $result[1]['width']);
        $this->assertEquals(500, $result[1]['height']);
    }
}
