<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\ImageHashException;
use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use App\Repositories\ImageHashRepositoryInterface;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class ImageHashRepositoryTest extends TestCase
{
    use RefreshTestDatabase;

    private ImageHashRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(ImageHashRepositoryInterface::class, ImageHashRepository::class);
        $this->repo = $this->app->make(ImageHashRepositoryInterface::class);
    }

    public function testIsNgReturnsFalse(): void
    {
        $result = $this->repo->isNg('nonexistenthash');

        $this->assertFalse($result);
    }

    public function testIsNgReturnsTrue(): void
    {
        ImageHash::factory()->create(['hash' => 'nghash', 'ng' => true]);

        $result = $this->repo->isNg('nghash');

        $this->assertTrue($result);
    }

    public function testIsNgReturnsFalseWhenNotNg(): void
    {
        ImageHash::factory()->create(['hash' => 'safehash', 'ng' => false]);

        $result = $this->repo->isNg('safehash');

        $this->assertFalse($result);
    }

    public function testFirstOrCreateWithImage(): void
    {
        $imageData = [
            'basename' => 'newimage',
            'ext'      => 'jpg',
            'original' => 'photo.jpg',
            'delkey'   => 'mykey',
            'mimetype' => 'image/jpeg',
            'size'     => 2048,
            'width'    => 640,
            'height'   => 480,
            'comment'  => 'new image',
            'ip'       => '192.168.1.1',
        ];

        $image = $this->repo->firstOrCreateWithImage('newhash123', $imageData);

        $this->assertNotNull($image);
        $this->assertEquals('newimage', $image->basename);
        $this->assertDatabaseHas('image_hashes', ['hash' => 'newhash123']);
        $this->assertDatabaseHas('images', ['basename' => 'newimage']);
    }

    public function testFirstOrCreateWithImageExistingHash(): void
    {
        $hash = ImageHash::factory()->create(['hash' => 'existinghash', 'ng' => false]);

        $imageData = [
            'basename' => 'anotherimage',
            'ext'      => 'png',
            'original' => 'pic.png',
            'delkey'   => 'key2',
            'mimetype' => 'image/png',
            'size'     => 512,
            'width'    => 320,
            'height'   => 240,
            'comment'  => 'another',
            'ip'       => '10.0.0.1',
        ];

        $image = $this->repo->firstOrCreateWithImage('existinghash', $imageData);

        $this->assertNotNull($image);
        $this->assertEquals($hash->id, $image->image_hash_id);
        $this->assertEquals(1, ImageHash::where('hash', 'existinghash')->count());
    }

    public function testFirstOrCreateWithImageThrowsOnNg(): void
    {
        ImageHash::factory()->create(['hash' => 'blockedhash', 'ng' => true]);

        $this->expectException(ImageHashException::class);

        $this->repo->firstOrCreateWithImage('blockedhash', [
            'basename' => 'blocked',
            'ext'      => 'jpg',
            'original' => 'blocked.jpg',
            'delkey'   => 'key',
            'mimetype' => 'image/jpeg',
            'size'     => 100,
            'width'    => 100,
            'height'   => 100,
            'comment'  => 'blocked',
            'ip'       => '1.2.3.4',
        ]);
    }
}
