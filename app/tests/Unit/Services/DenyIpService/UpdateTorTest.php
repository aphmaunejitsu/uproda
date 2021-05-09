<?php

namespace Tests\Unit\Services\DenyIpService;

use App\Services\DenyIpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @group Service
 * @group make:tor
 * @group DenyIpService
 * @group UpdateTorTest
 */
class UpdateTorTest extends TestCase
{
    use RefreshDatabase;

    private $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DenyIpService();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSuccess()
    {
        Http::fake([
            'check.torproject.org/exit-addresses' => Http::response("ExitAddress 10.11.12.13 1234\nExitAddress 20.21.22.23 2345", 200),
        ]);

        $result = $this->service->updateTor();

        $this->assertCount(2, $result);
        $this->assertEquals('10.11.12.13', $result[0]->ip);
        $this->assertTrue($result[0]->is_tor);
    }

    public function testNotGet()
    {
        Http::fake([
            'check.torproject.org/exit-addresses' => Http::response("aaaaaaaa2345", 200),
        ]);

        $result = $this->service->updateTor();

        $this->assertEmpty($result);
    }
}
