<?php

namespace Tests\Unit\Repositories\CommentRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\Comment;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\CommentRepository;

/**
 * @group GetByImageIdTest
 */
class GetByImageIdTest extends TestCase
{
    use RefreshDatabase;

    public $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );

        $this->repo = $this->app->make(CommentRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @dataProvider commentProvider
     * @return void
     */
    public function testComment($create, $get, $count)
    {
        $image = Image::factory()->has(Comment::factory()->count($create))->create();
        $result = $this->repo->getByImageId($image->id, $get);
        $this->assertCount($count, $result);
    }

    public function commentProvider()
    {
        return [
            ['create' => 100, 'get' => 10, 'count' => 10],
            ['create' => 100, 'get' => null, 'count' => 100],
        ];
    }
}
