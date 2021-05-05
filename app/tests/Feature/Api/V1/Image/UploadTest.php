<?php

namespace Tests\Feature\Api\V1\Image;

use App\Models\ImageHash;
use App\Services\Traits\ImageTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * @group Upload
 * @group Controller
 * @group UploadTest
 */
class UploadTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use ImageTrait;

    private $url;

    public function setUp(): void
    {
        parent::setUp();
        $this->url = route('v1.image.upload');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNoPost()
    {
        $response = $this->postJson($this->url);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'file',
                     'hash'
                 ]);
    }

    public function testValidateDelkey()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $delkey = '][;[';
        $comment = null;
        $hash = $this->faker->uuid;
        $json = compact('delkey', 'comment', 'hash', 'file');

        $response = $this->postJson($this->url, $json);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'delkey',
                 ])
                 ->assertJson([
                     'errors' => [
                         'delkey' => ['delkey は英数字とハイフン、アンダーバーを含めることができます']
                     ]
                 ]);
    }

    public function testValidateImageMimes()
    {
        $file = UploadedFile::fake()->create('test.mp4', 2);
        $delkey = 'colibri';
        $comment = 'test';
        $hash = $this->faker->uuid;
        $json = compact('delkey', 'comment', 'hash', 'file');


        $response = $this->postJson($this->url, $json);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'file',
                 ])
                 ->assertJson([
                     'errors' => [
                         'file' => ['アップロードできる画像は jpg, png, gif, bmp, webp です']
                     ]
                 ]);
    }

    public function testValidateImageSize()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $file->size(6);
        $delkey = 'colibri';
        $comment = 'test';
        $hash = $this->faker->uuid;
        $json = compact('delkey', 'comment', 'hash', 'file');


        $response = $this->postJson($this->url, $json);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'file',
                 ])
                 ->assertJson([
                     'errors' => [
                         'file' => ['アップロードできる画像サイズは 5 KB までです']
                     ]
                 ]);
    }

    public function testValidateImageNgHash()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $delkey = 'colibri';
        $comment = 'test';
        $hash = $this->faker->uuid;
        $hash = null;
        $json = compact('delkey', 'comment', 'hash', 'file');

        ImageHash::factory()->create([
            'hash' => $this->getHash($file),
            'ng'   => true
        ]);

        $response = $this->postJson($this->url, $json);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'file',
                 ])
                 ->assertJson([
                     'errors' => [
                         'file' => ['It is NG file']
                     ]
                 ]);
    }
}
