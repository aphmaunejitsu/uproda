<?php

namespace Tests\Unit\Repositories;

use App\Repositories\GoogleRecaptchaRepository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GoogleRecaptchaRepositoryTest extends TestCase
{
    private GoogleRecaptchaRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new GoogleRecaptchaRepository();
        config([
            'roda.google.recaptcha.verify' => 'https://www.google.com/recaptcha/api/siteverify',
            'roda.google.recaptcha.secret' => 'test-secret-key',
        ]);
    }

    public function testVerifySuccess(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'challenge_ts' => '2024-01-01T00:00:00Z',
                'hostname' => 'example.com',
            ], 200),
        ]);

        $response = $this->repository->verify('test-token', '127.0.0.1');

        $this->assertTrue($response->json('success'));
    }

    public function testVerifyReturnsResponse(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200),
        ]);

        $response = $this->repository->verify('invalid-token', '127.0.0.1');

        $this->assertFalse($response->json('success'));
        $this->assertContains('invalid-input-response', $response->json('error-codes'));
    }

    public function testVerifyReturnsFalseWhenUrlNotConfigured(): void
    {
        config(['roda.google.recaptcha.verify' => null]);

        $result = $this->repository->verify('token', '127.0.0.1');

        $this->assertFalse($result);
    }

    public function testVerifySendsCorrectPostData(): void
    {
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
            ], 200),
        ]);

        $this->repository->verify('my-token', '192.168.0.1');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://www.google.com/recaptcha/api/siteverify'
                && $request['secret'] === 'test-secret-key'
                && $request['response'] === 'my-token'
                && $request['remoteip'] === '192.168.0.1';
        });
    }
}
