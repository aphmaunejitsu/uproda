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
 * @group upload
 * @group DenyIpRepository
 * @group FindByIpTest
 */
class FindByIpTest extends TestCase
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

    public function testFindByIp()
    {
        $ip = $this->faker->ipv4;
        DenyIp::factory()->create([
            'ip' => $ip
        ]);

        $result = $this->repo->findByIp($ip);
        $this->assertEquals($ip, $result->ip);
    }

    public function testNotFound()
    {
        $ip = $this->faker->ipv4;
        DenyIp::factory()->create([
            'ip' => $this->faker->ipv4
        ]);

        $result = $this->repo->findByIp($ip);
        $this->assertEmpty($result);
    }
}
