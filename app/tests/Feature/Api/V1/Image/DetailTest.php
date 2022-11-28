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

    public $imagesOK;
    public $imagesNG;

    public function setUp(): void
    {
        parent::setUp();
        $this->imagesOK = Image::factory()
            ->count(10)
            ->forImageHash([
                'ng' => 0,
            ])
            ->create();

        $this->imagesNG = Image::factory()
            ->count(10)
            ->forImageHash([
                'ng' => 1,
            ])
            ->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNoData()
    {
        $url = route('v1.image.detail', ['basename' => 'xxxxx']);
        $response = $this->get($url);

        $this->assertEquals('http://testing.com/api/v1/image/xxxxx', $url);
        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'not found'
                 ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSuccess()
    {
        $url = route('v1.image.detail', ['basename' => $this->imagesOK[1]->basename]);
        $response = $this->get($url);

        $response->assertStatus(200);
    }

    public function testNg()
    {
        $url = route('v1.image.detail', ['basename' => $this->imagesNG[1]->basename]);
        $response = $this->get($url);

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'not found'
                 ]);
    }
}
