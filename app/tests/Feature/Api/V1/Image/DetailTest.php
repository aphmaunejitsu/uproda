<?php

namespace Tests\Feature\Api\V1\Image;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Image;

/**
 * @group api/v1/image/detail
 * @group DetailTest
 * @group FindByBaseNameAPi
 *
 */
class DetailTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotFound()
    {
        $url = route('v1.image.detail', ['basename' => 'xxxxx']);
        $this->assertEquals('http://testing.com/api/v1/image/xxxxx', $url);
        $response = $this->get(
            route('v1.image.detail', ['basename' => 'xxxxx'])
        );

        $response->assertStatus(404);
    }
}
