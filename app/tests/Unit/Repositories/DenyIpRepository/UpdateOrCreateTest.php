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
 * @group UpdateOrCreateTest
 */
class UpdateOrCreateTest extends TestCase
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

    public function testCreate()
    {
        $ip = $this->faker->ipv4;
        $result = $this->repo->updateOrCreate($ip, true);
        $this->assertInstanceOf(DenyIp::class, $result);
        $this->assertEquals($ip, $result->ip);
        $this->assertTrue($result->is_tor);
    }

    public function testUpdate()
    {
        $ip = $this->faker->ipv4;
        DenyIp::factory()->create([
            'ip'     => $ip,
            'is_tor' => false
        ]);

        $result = $this->repo->updateOrCreate($ip, true);
        $this->assertInstanceOf(DenyIp::class, $result);
        $this->assertEquals($ip, $result->ip);
        $this->assertTrue($result->is_tor);
    }
}
