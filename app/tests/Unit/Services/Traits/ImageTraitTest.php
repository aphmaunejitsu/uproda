<?php

namespace Tests\Unit\Services\Traits;

use Tests\TestCase;
use App\Services\Traits\ImageTrait;
use Illuminate\Http\UploadedFile;

/**
 * @group Service
 * @group ImageTraitTest
 *
 */
class ImageTraitTest extends TestCase
{
    private $trait;
    public function setUp(): void
    {
        parent::setUp();

        $this->trait = $this->getMockForTrait(ImageTrait::class);
    }

    public function testGetHash()
    {
        $thumb = UploadedFile::fake()->image('test.png', 1, 1);
        $file = $thumb->getRealPath();
        $result = $this->trait->getHash($file);
        $this->assertIsString($result);
        $this->assertEquals('4VN09ERNHB3LEQOE4MDN9893E2', $result);
    }

    /**
     * @dataProvider generateBasenameProvider
     */
    public function testGenerateBasename($length, $expected)
    {
        $result = $this->trait->generateBasename($length);
        $this->assertIsString($result);
        $this->assertEquals($expected, strlen($result));
    }

    public static function generateBasenameProvider()
    {
        return [
            'length-8'  => [8, 8],
            'length-16' => [16, 16],
        ];
    }
}
