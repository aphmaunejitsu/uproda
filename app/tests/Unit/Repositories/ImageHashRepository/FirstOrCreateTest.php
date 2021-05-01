<?php

namespace Tests\Unit\Repositories\ImageHashRepository;

use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use App\Repositories\ImageHashRepositoryInterface;
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
        $imageHash = $this->repo->firstOrCreate('abc', false, 'test');

        $this->assertInstanceOf(ImageHash::class, $imageHash);
        $this->assertEquals('abc', $imageHash->hash);
        $this->assertFalse($imageHash->ng);
        $this->assertEquals('test', $imageHash->comment);
    }

    public function testAlredyExists()
    {
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
    }
}
