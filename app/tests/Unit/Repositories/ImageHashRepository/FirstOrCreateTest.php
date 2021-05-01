<?php

namespace Tests\Unit\Repositories\ImageHashRepository;

use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use App\Repositories\ImageHashRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group upload
 * @group ImageHashRepository
 * @group FirstOrCreateTest
 */
class FirstOrCreateTest extends TestCase
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

        $imageHash = $this->repo->firstOrCreate('abc', false, 'test');

        $this->assertInstanceOf(ImageHash::class, $imageHash);
        $this->assertEquals('abc', $imageHash->hash);
        $this->assertFalse($imageHash->ng);
        $this->assertEquals('test', $imageHash->comment);
        $this->assertEquals('2021-05-01 00:01:02', $imageHash->created_at);
        $this->assertEquals('2021-05-01 00:01:02', $imageHash->updated_at);
    }

    public function testAlredyExists()
    {
        Carbon::setTestNow(new Carbon('2020-05-01 00:01:02'));
        ImageHash::factory()->create([
            'hash'    => 'xyz',
            'ng'      => true,
            'comment' => 'already',
        ]);
        $imageHash = $this->repo->firstOrCreate('xyz', false, 'test');

        $this->assertInstanceOf(ImageHash::class, $imageHash);
        $this->assertEquals('xyz', $imageHash->hash);
        $this->assertTrue($imageHash->ng);
        $this->assertEquals('already', $imageHash->comment);
        $this->assertEquals('2020-05-01 00:01:02', $imageHash->created_at);
        $this->assertEquals('2020-05-01 00:01:02', $imageHash->updated_at);
    }
}
