<?php

namespace Tests\Unit\Repositories\TorRepository;

use App\Repositories\TorRepository;
use App\Repositories\TorRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @group Repository
 * @group TorRepository
 * @group GetTest
 */
class GetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            TorRepositoryInterface::class,
            TorRepository::class
        );

        $this->repo = $this->app->make(TorRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGet()
    {
        Http::fake([
            'check.torproject.org/exit-addresses' => Http::response("ExitAddress 10.11.12.13 1234\nExitAddress 20.21.22.23 2345", 200),
        ]);

        $result = $this->repo->get();
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
    }

    public function testNotGet()
    {
        Http::fake([
            'check.torproject.org/exit-addresses' => Http::response("aaaaaaaaaaa", 200),
        ]);

        $result = $this->repo->get();
        $this->assertEmpty($result);
    }
}
