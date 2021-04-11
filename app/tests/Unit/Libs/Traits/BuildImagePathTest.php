<?php

namespace Tests\Unit\Libs\Traits;

use Tests\TestCase;
use App\Libs\Traits\BuildImagePath;

/**
 * @group BuildImagePathTest
 */
class BuildImagePathTest extends TestCase
{
    private $trait;
    public function setUp(): void
    {
        parent::setUp();

        $this->trait = $this->getMockForTrait(BuildImagePath::class);
    }

    public function testGetImageStorage()
    {
        $image = $this->trait->getImageStorage();
        $this->assertEquals('image', $image);
    }

    public function testGetSaveDirectory()
    {
        $image = $this->trait->getSaveDirectory('Abc');
        $this->assertEquals('a', $image);
    }

    public function testBuildThumbnailDir()
    {
        $image = $this->trait->buildThumbnailDir('Abc');
        $this->assertEquals('/a/thumbnail', $image);
    }

    public function testBuildFilename()
    {
        $image = $this->trait->buildFilename('Abc', 'png');
        $this->assertEquals('Abc.png', $image);
    }

    public function testBuildThumbnailPath()
    {
        $image = $this->trait->buildThumbnailPath('Abc', 'png');
        $this->assertEquals('/a/thumbnail/Abc.png', $image);
    }

    public function testBuildImagePath()
    {
        $image = $this->trait->buildImagePath('Abc', 'png');
        $this->assertEquals('/a/Abc.png', $image);
    }

    public function testGetThumbnailUrl()
    {
        $image = $this->trait->getThumbnailUrl('Abc', 'jpg');
        $this->assertEquals('http://testing.com/a/thumbnail/Abc.jpg', $image);
    }

    public function testGetImageUrl()
    {
        $image = $this->trait->getImageUrl('Abc', 'jpg');
        $this->assertEquals('http://testing.com/a/Abc.jpg', $image);
    }
}
