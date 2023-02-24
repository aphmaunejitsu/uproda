<?php

namespace Tests\Unit\Services\GoogleRecaptchaService;

use App\Repositories\GoogleRecaptchaRepositoryInterface;
use App\Services\GoogleRecaptchaService as Recaptcha;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery\MockInterface;

/**
 * @group Repository
 * @group GoogleRecaptcha
 * @group GoogleRecaptchaService
 * @group GoogleRecaptcha::VerifyTest
 *
 */
class VerifyTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testVerify()
    {
        $this->mock(
            GoogleRecaptchaRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('verify')->andReturn(
                    new class {
                        public function json()
                        {
                            return [
                                'success' => true,
                                'score'   => 0.6,
                            ];
                        }
                    }
                );
            }
        );

        $result = (new Recaptcha())->verify(
            $this->faker->uuid,
            $this->faker->ipv4,
            $this->faker->linuxPlatformToken
        );

        $this->assertTrue($result);
    }
}
