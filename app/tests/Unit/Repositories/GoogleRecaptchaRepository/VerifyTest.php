<?php

namespace Tests\Unit\Repositories\GoogleRecaptchaRepository;

use App\Repositories\GoogleRecaptchaRepository;
use App\Repositories\GoogleRecaptchaRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @group Repository
 * @group GoogleRecaptcha
 * @group GoogleRecaptchaRepository
 * @group VerifyTest
 */
class VerifyTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            GoogleRecaptchaRepositoryInterface::class,
            GoogleRecaptchaRepository::class
        );

        $this->repo = $this->app->make(GoogleRecaptchaRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testVerify()
    {
        Http::fake([
            'www.google.com/recaptcha/*' => Http::response([
                'success' => true,
                'challenge_ts' => time(),
                'hostname'     => 'xxx',
            ], 200),
        ]);

        $result = $this->repo->verify('11.1.1.1', 'token');
        $this->assertNotEmpty($result);
    }
}
