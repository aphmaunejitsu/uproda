<?php

namespace Tests\Unit\Repositories;

use App\Repositories\TorRepository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TorRepositoryTest extends TestCase
{
    private TorRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new TorRepository();
        config(['roda.url.tor' => 'https://example.com/tor-exit-list']);
    }

    public function testGetReturnsTorIps(): void
    {
        Http::fake([
            'https://example.com/tor-exit-list' => Http::response(
                "ExitAddress 192.168.1.1 2024-01-01 00:00:00\nExitAddress 10.0.0.1 2024-01-01 00:00:00\n",
                200
            ),
        ]);

        $result = $this->repository->get();

        $this->assertCount(2, $result);
        $this->assertEquals('192.168.1.1', $result[0]);
        $this->assertEquals('10.0.0.1', $result[1]);
    }

    public function testGetReturnsEmptyWhenNoExitAddresses(): void
    {
        Http::fake([
            'https://example.com/tor-exit-list' => Http::response(
                "Published 2024-01-01\nLastStatus 2024-01-01\nSomeOtherData line\n",
                200
            ),
        ]);

        $result = $this->repository->get();

        $this->assertCount(0, $result);
    }

    public function testGetHandlesMultipleExitAddresses(): void
    {
        $body = implode("\n", [
            'ExitAddress 1.1.1.1 2024-01-01 00:00:00',
            'Published 2024-01-01',
            'ExitAddress 2.2.2.2 2024-01-01 00:00:00',
            'LastStatus 2024-01-01',
            'ExitAddress 3.3.3.3 2024-01-01 00:00:00',
        ]);

        Http::fake([
            'https://example.com/tor-exit-list' => Http::response($body, 200),
        ]);

        $result = $this->repository->get();

        $this->assertCount(3, $result);
        $this->assertEquals('1.1.1.1', $result[0]);
        $this->assertEquals('2.2.2.2', $result[1]);
        $this->assertEquals('3.3.3.3', $result[2]);
    }

    public function testGetReturnsFalseWhenUrlNotConfigured(): void
    {
        config(['roda.url.tor' => null]);

        $result = $this->repository->get();

        $this->assertFalse($result);
    }
}
