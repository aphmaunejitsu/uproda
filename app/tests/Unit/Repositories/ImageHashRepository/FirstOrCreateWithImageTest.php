<?php

namespace Tests\Unit\Repositories\ImageHashRepository;

use App\Exceptions\ImageHashException;
use App\Models\Image;
use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use App\Repositories\ImageHashRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group upload
 * @group ImageHashRepository
 * @group FirstOrCreateWithImageTest
 */
class FirstOrCreateWithImageTest extends TestCase
{
    use RefreshDatabase;

    private $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            ImageHashRepositoryInterface::class,
            ImageHashRepository::class,
        );

        $this->repo = $this->app->make(ImageHashRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFirstOrCreate()
    {
        Carbon::setTestNow(new Carbon('2021-05-01 00:01:02'));

        $image = Image::factory()->make();

        $result = $this->repo->firstOrCreateWithImage('abc', $image->toArray(), false, 'test');

        $this->assertInstanceOf(ImageHash::class, $result->imageHash);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals('abc', $result->imageHash->hash);
        $this->assertFalse($result->imageHash->ng);
        $this->assertEquals('test', $result->imageHash->comment);
        $this->assertEquals('2021-05-01 00:01:02', $result->created_at);
        $this->assertEquals('2021-05-01 00:01:02', $result->updated_at);
    }

    public function testExceptionNg()
    {
        $this->expectException(ImageHashException::class);
        ImageHash::factory()->create([
            'hash'    => 'xyz',
            'ng'      => true,
            'comment' => 'already',
        ]);
        $image = Image::factory()->make();
        $imageHash = $this->repo->firstOrCreateWithImage('xyz', $image->toArray(), false, 'test');
    }

    public function testAlredyExists()
    {
        Carbon::setTestNow(new Carbon('2020-05-01 00:01:02'));
        ImageHash::factory()->create([
            'hash'    => 'xyz',
            'ng'      => false,
            'comment' => 'already',
        ]);
        $image = Image::factory()->make();
        $result = $this->repo->firstOrCreateWithImage('xyz', $image->toArray(), false, 'test');

        $this->assertInstanceOf(ImageHash::class, $result->imageHash);
        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals('xyz', $result->imageHash->hash);
        $this->assertFalse($result->imageHash->ng);
        $this->assertEquals('already', $result->imageHash->comment);
        $this->assertEquals('2020-05-01 00:01:02', $result->imageHash->created_at);
        $this->assertEquals('2020-05-01 00:01:02', $result->imageHash->updated_at);
    }
}
