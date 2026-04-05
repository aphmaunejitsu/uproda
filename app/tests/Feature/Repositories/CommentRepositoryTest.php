<?php

namespace Tests\Feature\Repositories;

use App\Models\Comment;
use App\Models\Image;
use App\Models\ImageHash;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshTestDatabase;

    private CommentRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->repo = $this->app->make(CommentRepositoryInterface::class);
    }

    public function testGetByImageId(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create(['image_hash_id' => $hash->id]);
        Comment::factory()->count(5)->create(['image_id' => $image->id]);

        $result = $this->repo->getByImageId($image->id);

        $this->assertCount(5, $result);
    }

    public function testGetByImageIdWithLimit(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create(['image_hash_id' => $hash->id]);
        Comment::factory()->count(5)->create(['image_id' => $image->id]);

        $result = $this->repo->getByImageId($image->id, 3);

        $this->assertCount(3, $result);
    }

    public function testGetByImageIdNoComments(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create(['image_hash_id' => $hash->id]);

        $result = $this->repo->getByImageId($image->id);

        $this->assertCount(0, $result);
    }
}
