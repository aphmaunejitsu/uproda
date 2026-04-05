<?php

namespace Tests\Unit\Traits;

use App\Libs\Traits\BuildImagePath;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BuildImagePathTest extends TestCase
{
    private $trait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->trait = $this->getMockForTrait(BuildImagePath::class);
    }

    public function testGetImageStorage(): void
    {
        $this->assertEquals('image', $this->trait->getImageStorage());
    }

    public function testGetChunkStorage(): void
    {
        $this->assertEquals('chunk', $this->trait->getChunkStorage());
    }

    public function testGetSaveDirectory(): void
    {
        $this->assertEquals('a', $this->trait->getSaveDirectory('Abc'));
        $this->assertEquals('x', $this->trait->getSaveDirectory('xyz'));
    }

    public function testBuildThumbnailDir(): void
    {
        $this->assertEquals('/a/thumbnail', $this->trait->buildThumbnailDir('Abc'));
    }

    public function testBuildFilename(): void
    {
        $this->assertEquals('Abc.png', $this->trait->buildFilename('Abc', 'png'));
    }

    public function testBuildThumbnailPath(): void
    {
        $this->assertEquals('/a/thumbnail/Abc.png', $this->trait->buildThumbnailPath('Abc', 'png'));
    }

    public function testBuildImagePath(): void
    {
        $this->assertEquals('/a/Abc.png', $this->trait->buildImagePath('Abc', 'png'));
    }

    public function testBuildMergedPath(): void
    {
        $this->assertEquals('/Abc/merged', $this->trait->buildMergedPath('Abc'));
    }

    public function testGetImageUrlUsesStorageUrl(): void
    {
        Storage::fake('image');

        $url = $this->trait->getImageUrl('Abc', 'jpg');

        $this->assertStringContainsString('Abc.jpg', $url);
    }

    public function testGetThumbnailUrlReturnsNoimageWhenExtIsNull(): void
    {
        $url = $this->trait->getThumbnailUrl('Abc', null);

        $this->assertStringContainsString('noimage.png', $url);
    }

    public function testGetThumbnailUrlReturnsNoimageWhenFileNotExists(): void
    {
        Storage::fake('image');

        $url = $this->trait->getThumbnailUrl('Abc', 'jpg');

        $this->assertStringContainsString('noimage.png', $url);
    }
}
