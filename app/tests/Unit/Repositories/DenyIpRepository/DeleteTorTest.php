<?php

namespace Tests\Unit\Repositories\DenyIpRepository;

use App\Models\DenyIp;
use App\Repositories\DenyIpRepository;
use App\Repositories\DenyIpRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group Repository
 * @group tor:update
 * @group DenyIpRepository
 * @group DeleteTorTest
 */
class DeleteTorTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            DenyIpRepositoryInterface::class,
            DenyIpRepository::class
        );

        $this->repo = $this->app->make(DenyIpRepositoryInterface::class);
    }

    public function testDeleteTor()
    {
        $ip = $this->faker->ipv4;
        DenyIp::factory()->count(1)->create([
            'ip'     => $ip,
            'is_tor' => true
        ]);
        DenyIp::factory()->count(10)->create([
            'is_tor' => true
        ]);
        DenyIp::factory()->count(10)->create([
            'is_tor' => false
        ]);

        $result = $this->repo->deleteTorByIp($ip);
        $this->assertEquals(1, $result);
    }

    public function testNotDeleteTor()
    {
        $ip = $this->faker->ipv4;
        DenyIp::factory()->count(10)->create([
            'is_tor' => false
        ]);

        $result = $this->repo->deleteTorByIp($ip);
        $this->assertEquals(0, $result);
    }
}
