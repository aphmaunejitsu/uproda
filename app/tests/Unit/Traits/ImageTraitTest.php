<?php

namespace Tests\Unit\Traits;

use App\Services\Traits\ImageTrait;
use Tests\TestCase;

class ImageTraitTest extends TestCase
{
    private $trait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->trait = $this->getMockForTrait(ImageTrait::class);
    }

    public function testGetHash(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'test_');
        file_put_contents($tmpFile, 'test content for hashing');

        $hash = $this->trait->getHash($tmpFile);

        $this->assertEquals(26, strlen($hash));
        $this->assertMatchesRegularExpression('/^[0-9A-V]+$/', $hash);

        unlink($tmpFile);
    }

    public function testGenerateBasename(): void
    {
        $basename = $this->trait->generateBasename();

        $this->assertEquals(8, strlen($basename));
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $basename);
    }

    public function testGenerateBasenameIsRandom(): void
    {
        $first = $this->trait->generateBasename();
        $second = $this->trait->generateBasename();

        $this->assertNotEquals($first, $second);
    }

    public function testMimeTypeToExtension(): void
    {
        $this->assertEquals('.jpg', $this->trait->mimeTypeToExtension('image/jpeg'));
        $this->assertEquals('.png', $this->trait->mimeTypeToExtension('image/png'));
        $this->assertEquals('.gif', $this->trait->mimeTypeToExtension('image/gif'));
        $this->assertEquals('.webp', $this->trait->mimeTypeToExtension('image/webp'));
        $this->assertEquals('.bmp', $this->trait->mimeTypeToExtension('image/bmp'));
    }

    public function testMimeTypeToExtensionWithoutDot(): void
    {
        $this->assertEquals('jpg', $this->trait->mimeTypeToExtension('image/jpeg', false));
        $this->assertEquals('png', $this->trait->mimeTypeToExtension('image/png', false));
    }

    public function testMimeTypeToExtensionReturnsNull(): void
    {
        $this->assertNull($this->trait->mimeTypeToExtension('image/tiff'));
        $this->assertNull($this->trait->mimeTypeToExtension('application/pdf'));
    }
}
