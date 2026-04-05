<?php

namespace Tests\Feature\Repositories;

use App\Models\Comment;
use App\Models\Image;
use App\Models\ImageHash;
use App\Repositories\ImageRepository;
use App\Repositories\ImageRepositoryInterface;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class ImageRepositoryTest extends TestCase
{
    use RefreshTestDatabase;

    private ImageRepositoryInterface $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->repo = $this->app->make(ImageRepositoryInterface::class);
    }

    public function testCreate(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);

        $data = [
            'image_hash_id' => $hash->id,
            'basename'      => 'testimage',
            'ext'           => 'png',
            'original'      => 'original.png',
            'delkey'        => 'secret',
            'mimetype'      => 'image/png',
            'size'          => 1024,
            'width'         => 800,
            'height'        => 600,
            'comment'       => 'test comment',
            'ip'            => '127.0.0.1',
        ];

        $image = $this->repo->create($data);

        $this->assertDatabaseHas('images', ['basename' => 'testimage']);
        $this->assertEquals('testimage', $image->basename);
    }

    public function testFindByBasename(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create([
            'image_hash_id' => $hash->id,
            'basename'      => 'findme',
        ]);
        Comment::factory()->create(['image_id' => $image->id]);

        $found = $this->repo->findByBasename('findme');

        $this->assertNotNull($found);
        $this->assertEquals('findme', $found->basename);
        $this->assertTrue($found->relationLoaded('imageHash'));
        $this->assertTrue($found->relationLoaded('comments'));
    }

    public function testFindByBasenameNotFound(): void
    {
        $result = $this->repo->findByBasename('nonexistent');

        $this->assertNull($result);
    }

    public function testPaginate(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        Image::factory()->count(15)->create(['image_hash_id' => $hash->id]);

        $result = $this->repo->paginate(10);

        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
    }

    public function testSaveComment(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create(['image_hash_id' => $hash->id]);

        $comment = $this->repo->saveComment($image->id, 'Hello world');

        $this->assertNotNull($comment);
        $this->assertEquals('Hello world', $comment->comment);
        $this->assertEquals($image->id, $comment->image_id);
        $this->assertDatabaseHas('comments', [
            'image_id' => $image->id,
            'comment'  => 'Hello world',
        ]);
    }

    public function testUpdateGeometry(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create([
            'image_hash_id' => $hash->id,
            'width'         => 100,
            'height'        => 100,
        ]);

        $updated = $this->repo->updateGeometry($image->id, 1920, 1080);

        $this->assertEquals(1920, $updated->width);
        $this->assertEquals(1080, $updated->height);
        $this->assertDatabaseHas('images', [
            'id'     => $image->id,
            'width'  => 1920,
            'height' => 1080,
        ]);
    }

    public function testDelete(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create([
            'image_hash_id' => $hash->id,
            'basename'      => 'deleteme',
            'delkey'        => 'correctkey',
        ]);

        $result = $this->repo->deleteByBasename('deleteme', 'correctkey');

        $this->assertNotNull($result);
        $this->assertSoftDeleted('images', ['id' => $image->id]);
    }

    public function testDeleteWrongKey(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        Image::factory()->create([
            'image_hash_id' => $hash->id,
            'basename'      => 'deleteme',
            'delkey'        => 'correctkey',
        ]);

        $result = $this->repo->deleteByBasename('deleteme', 'wrongkey');

        $this->assertNull($result);
        $this->assertDatabaseHas('images', ['basename' => 'deleteme']);
    }

    public function testDeleteByImageHash(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false, 'hash' => 'abc123']);
        Image::factory()->count(3)->create(['image_hash_id' => $hash->id]);

        $deleted = $this->repo->deleteByImageHash('abc123');

        $this->assertCount(3, $deleted);
        foreach ($deleted as $image) {
            $this->assertSoftDeleted('images', ['id' => $image->id]);
        }
    }

    public function testSetNG(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create([
            'image_hash_id' => $hash->id,
            'basename'      => 'ngimage',
        ]);

        $result = $this->repo->setNgByBasename('ngimage');

        $this->assertNotNull($result);
        $hash->refresh();
        $this->assertTrue($hash->ng);
    }

    public function testGetByIds(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $images = Image::factory()->count(5)->create(['image_hash_id' => $hash->id]);

        $targetIds = $images->take(3)->pluck('id')->toArray();
        $result = $this->repo->getByIds($targetIds);

        $this->assertCount(3, $result);
    }
}
