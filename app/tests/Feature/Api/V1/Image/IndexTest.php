<?php

namespace Tests\Feature\Api\V1\Image;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Image;

/**
 * @group api/v1/image/index
 * @group IndexTest
 */
class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Image::factory()
            ->count(201)
            ->forImageHash([
                'ng' => 0,
            ])
            ->create();
        Image::factory()
            ->count(201)
            ->forImageHash([
                'ng' => 1,
            ])
            ->create();
    }

    /**
     * @group testPaginate
     */
    public function testPaginate()
    {
        $response = $this->get(route('v1.image.index'));

        $response->assertStatus(200)
                 ->assertJson([
                     'meta' => [
                         'total'        => 201,
                         'current_page' => 1,
                         'from'         => 1,
                         'to'           => 100
                     ]
                 ]);
    }

    /**
     * A basic feature test example.
     *
     * @dataProvider paginateProvider
     * @return void
     */
    public function testPaginateWithParameter($page, $current, $from, $to, $total)
    {
        $response = $this->get(route('v1.image.index', ['page' => $page]));

        $response->assertStatus(200)
                 ->assertJson([
                     'meta' => [
                         'total'        => $total,
                         'current_page' => $current,
                         'from'         => $from,
                         'to'           => $to
                     ],
                 ]);
    }

    public function paginateProvider()
    {
        return [
            ['page' => 1,  'current' => 1, 'from' =>   1, 'to' => 100, 'total' => 201],
            ['page' => 2,  'current' => 2, 'from' => 101, 'to' => 200, 'total' => 201],
            ['page' => 3,  'current' => 3, 'from' => 201, 'to' => 201, 'total' => 201],
        ];
    }
}
