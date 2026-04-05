<?php

namespace Tests\Feature\Api;

use App\Models\Image;
use App\Models\ImageHash;
use Tests\RefreshTestDatabase;
use Tests\TestCase;

class ImageApiTest extends TestCase
{
    use RefreshTestDatabase;

    public function testIndex(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        Image::factory()->count(3)->create(['image_hash_id' => $hash->id]);

        $response = $this->getJson('/api/v1/image');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
        $this->assertCount(3, $response->json('data'));
    }

    public function testDetail(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create([
            'image_hash_id' => $hash->id,
            'basename'      => 'detailtest',
        ]);

        $response = $this->getJson('/api/v1/image/detailtest');

        $response->assertStatus(200);
        $response->assertJsonPath('data.basename', 'detailtest');
    }

    public function testDetailNotFound(): void
    {
        $response = $this->getJson('/api/v1/image/nonexistent');

        $response->assertStatus(404);
    }

    public function testDelete(): void
    {
        $hash = ImageHash::factory()->create(['ng' => false]);
        $image = Image::factory()->create([
            'image_hash_id' => $hash->id,
            'basename'      => 'deletable',
            'delkey'        => 'correctdelkey',
        ]);

        $response = $this->deleteJson('/api/v1/image', [
            'basename' => 'deletable',
            'delkey'   => 'correctdelkey',
        ]);

        $response->assertStatus(204);
    }

    public function testDeleteValidationError(): void
    {
        $response = $this->deleteJson('/api/v1/image', []);

        $response->assertStatus(422);
    }
}
