<?php

namespace Tests\Unit\Libs\Traits;

use Tests\TestCase;
use Mockery\MockInterface;
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

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetImageUrl()
    {
        $image = $this->trait->getImageUrl('Abc', 'jpg');
        $this->assertEquals('http://testing.com/a/Abc.jpg', $image);
    }

    public function testGetThumbnailUrl()
    {
        $image = $this->trait->getThumbnailUrl('Abc', 'jpg');
        $this->assertEquals('http://testing.com/a/thumbnail/Abc.jpg', $image);
    }

    public function testGetSaveDirectory()
    {
        $image = $this->trait->getSaveDirectory('Abc');
        $this->assertEquals('a', $image);
    }
}
