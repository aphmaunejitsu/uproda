<?php

namespace Tests\Unit\Repositories\ImageRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Models\Comment;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

/**
 * @group ImageRepository
 * @group SaveCommentTest
 */
class SaveCommentTest extends TestCase
{
    use RefreshDatabase;

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
    public function testSaveComment()
    {
        $image = Image::factory()->create();

        $comment = $this->repo->saveComment($image->id, 'test comment');

        $this->assertEquals('test comment', $comment->comment);
    }

    public function testFail()
    {
        $result = $this->repo->saveComment(0, 'test');
        $this->assertNull($result);
    }
}
