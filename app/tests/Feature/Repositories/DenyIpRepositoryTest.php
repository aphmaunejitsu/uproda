<?php

namespace Tests\Feature\Repositories;

use App\Models\DenyIp;
use App\Repositories\DenyIpRepository;
use App\Repositories\DenyIpRepositoryInterface;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class DenyIpRepositoryTest extends TestCase
{
    use RefreshTestDatabase;

    private DenyIpRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(DenyIpRepositoryInterface::class, DenyIpRepository::class);
        $this->repo = $this->app->make(DenyIpRepositoryInterface::class);
    }

    public function testFindByIp(): void
    {
        DenyIp::factory()->create(['ip' => '192.168.1.100', 'is_tor' => false]);

        $result = $this->repo->findByIp('192.168.1.100');

        $this->assertNotNull($result);
        $this->assertEquals('192.168.1.100', $result->ip);
    }

    public function testFindByIpNotFound(): void
    {
        $result = $this->repo->findByIp('10.0.0.1');

        $this->assertNull($result);
    }

    public function testDeleteTor(): void
    {
        DenyIp::factory()->create(['ip' => '1.1.1.1', 'is_tor' => true]);
        DenyIp::factory()->create(['ip' => '2.2.2.2', 'is_tor' => true]);
        DenyIp::factory()->create(['ip' => '3.3.3.3', 'is_tor' => false]);

        $this->repo->deleteTorByIp('1.1.1.1');

        $this->assertDatabaseMissing('deny_ips', ['ip' => '1.1.1.1']);
        $this->assertDatabaseHas('deny_ips', ['ip' => '2.2.2.2']);
        $this->assertDatabaseHas('deny_ips', ['ip' => '3.3.3.3']);
    }

    public function testUpdateOrCreate(): void
    {
        $entry = $this->repo->updateOrCreate('5.5.5.5', true);

        $this->assertNotNull($entry);
        $this->assertEquals('5.5.5.5', $entry->ip);
        $this->assertTrue($entry->is_tor);
        $this->assertDatabaseHas('deny_ips', ['ip' => '5.5.5.5']);

        $updated = $this->repo->updateOrCreate('5.5.5.5', false);

        $this->assertFalse($updated->is_tor);
        $this->assertEquals(1, DenyIp::where('ip', '5.5.5.5')->count());
    }
}
