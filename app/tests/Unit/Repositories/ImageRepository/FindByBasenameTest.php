<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\ImageHash;
use App\Models\Comment;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group ImageRepository
 * @group FindByBasenameTest
 */
class FindByBasenameTest extends TestCase
{
    use RefreshDatabase;

    public $repo;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->bind(
            ImageRepositoryInterface::class,
            ImageRepository::class,
        );

        $this->repo = $this->app->make(ImageRepositoryInterface::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFindByBasename()
    {
        Image::factory()
            ->has(Comment::factory()->count(10))
            ->forImageHash(['ng' => 0])
            ->create(['basename' => 'abcde']);

        $result = $this->repo->findByBasename('abcde');
        $this->assertInstanceOf(Image::class, $result);
        $this->assertEquals(10, $result->comments_count);
    }

    /**
     * A basic unit test example.
     *
     * @dataProvider notFoundProvider
     * @return void
     */
    public function testNotFound($ng, $basename, $search)
    {
        Image::factory()
            ->has(Comment::factory()->count(10))
            ->forImageHash(['ng' => $ng])
            ->create(['basename' => $basename]);

        $result = $this->repo->findByBasename($search);
        $this->assertEmpty($result);
    }

    public function notFoundProvider()
    {
        return [
            ['ng' => 0, 'basename' => 'abcde', 'search' => 'xyz'],
            ['ng' => 1, 'basename' => 'abcde', 'search' => 'abcde']
        ];
    }
}
