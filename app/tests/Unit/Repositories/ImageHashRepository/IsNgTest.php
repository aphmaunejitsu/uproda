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
 * @group IsNgTest
 */
class IsNgTest extends TestCase
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
     * @dataProvider isNgProvider
     * @return void
     */
    public function testIsNg($hash, $ng, $is)
    {
        ImageHash::factory()->create([
            'hash' => $hash,
            'ng'   => $ng
        ]);

        $result = $this->repo->isNg($hash);
        $this->assertEquals($is, $result);
    }

    public function isNgProvider()
    {
        return [
            [
                'xyz',
                false,
                false
            ],
            [
                'abc',
                true,
                true,
            ],
        ];
    }

    public function testIsNgNotFound()
    {
        $result = $this->repo->isNg('aaaaaa');
        $this->assertFalse($result);
    }
}
