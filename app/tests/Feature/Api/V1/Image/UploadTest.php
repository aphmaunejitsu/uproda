<?php

namespace Tests\Feature\Api\V1\Image;

use App\Libs\Traits\BuildImagePath;
use App\Models\DenyWord;
use App\Models\Image;
use App\Models\ImageHash;
use App\Services\Traits\ImageTrait;
use App\Services\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery\MockInterface;

/**
 * @group upload
 * @group Controller
 * @group UploadTest
 */
class UploadTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use ImageTrait;
    use BuildImagePath;

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

    /**
     * @group testValidateComment
     */
    public function testValidateComment()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        DenyWord::factory()->create(['word' => 'FF14']);
        DenyWord::factory()->create(['word' => '女子高生']);
        $delkey  = null;
        $comment = 'FF14';
        $hash = $this->faker->uuid;
        $json = compact('delkey', 'comment', 'hash', 'file');

        $response = $this->postJson($this->url, $json);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors([
                     'comment',
                 ])
                 ->assertJson([
                     'errors' => [
                         'comment' => ['comment に禁止ワードが含まれています']
                     ]
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

    public function testValidateImageSize()
    {
        $file = UploadedFile::fake()->image('test.jpg');
        $kbytes = config('roda.upload.max');
        $file->size($kbytes + 1);
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
                         'file' => ["アップロードできる画像サイズは {$kbytes} KB までです"]
                     ]
                 ]);
    }

    public function testValidateImageNgHash()
    {
        Storage::fake('chunk');
        Storage::fake('image');
        Storage::fake('tmp');

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

    /**
     * @group testSingleupload
     */
    public function testSingleUpload()
    {
        Storage::fake('image');
        Storage::fake('tmp');
        Storage::fake('chunk');

        $file = UploadedFile::fake()->image('test.jpg');
        $file->size(1);
        $hash = $this->faker->uuid;
        $json = compact('hash', 'file');
        ImageHash::factory()->create([
            'hash' => $this->getHash($file),
            'ng'   => false
        ]);
        $response = $this->postJson($this->url, $json);

        $result = $response->getContent();
        $res = json_decode($result);

        $image = $this->buildImagePath($res->data->basename, $res->data->ext);
        $thumbnail = $this->buildThumbnailPath($res->data->basename, $res->data->t_ext);
        Storage::disk('image')->assertExists($image);
        Storage::disk('image')->assertExists($thumbnail);

        $response->assertStatus(201);
    }

    public function testFailedSingleUpload()
    {
        Storage::fake('image');
        Storage::fake('tmp');
        Storage::fake('chunk');

        $this->mock(
            UploadService::class,
            function (MockInterface $m) {
                $m->shouldReceive('uploaded')->andReturn(
                    null
                );
            }
        );

        $file = UploadedFile::fake()->image('test.jpg');
        $file->size(1);
        $hash = $this->faker->uuid;
        $json = compact('hash', 'file');
        ImageHash::factory()->create([
            'hash' => $this->getHash($file),
            'ng'   => false
        ]);
        $response = $this->postJson($this->url, $json);

        $response->assertStatus(400)
            ->assertJson(['message' => 'アップロードできませんでした']);
    }

    public function testSparateUpload()
    {
        Storage::fake('image');
        Storage::fake('tmp');
        Storage::fake('chunk');

        $test = Storage::disk('local')->get('test.jpg');

        $size = Storage::disk('local')->size('test.jpg');
        $mimetype = Storage::disk('local')->mimeType('test.jpg');
        $md5  = md5($test);
        Storage::fake('chunk');
        Storage::fake('image');
        Storage::fake('tmp');
        $bytes = 2048;
        $start = 0;

        $hash = $this->faker->uuid;

        while (true) {
            $split = substr($test, $start, $bytes);
            if (empty($split)) {
                break;
            }

            $file = UploadedFile::fake()->createWithContent("test_{$start}.jpg", $split);
            $json = compact('hash', 'file');
            $end = $start + ($file->getSize() - 1);
            $response = $this->withHeaders(['Content-Range' => "bytes {$start}-{$end}/{$size}"])
                             ->postJson($this->url, $json);
            $start += $bytes;
            sleep(1);
        }

        $result = $response->getContent();
        $res = json_decode($result);
        $image = $this->buildImagePath($res->data->basename, $res->data->ext);
        $thumbnail = $this->buildThumbnailPath($res->data->basename, $res->data->t_ext);
        Storage::disk('image')->assertExists($image);
        Storage::disk('image')->assertExists($thumbnail);
    }
}
