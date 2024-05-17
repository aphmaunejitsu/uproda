<?php

namespace Tests\Unit\Services\DenyWordService;

use App\Models\DenyWord;
use App\Services\DenyWordService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @group upload
 * @group Service
 * @group DenyWordService
 * @group GetTest
 */
class GetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetCache()
    {
        DenyWord::factory()->count(10)->create();

        $result = (new DenyWordService())->get();
        $cached = (new DenyWordService())->get();

        $this->assertSame($result, $cached);
    }

    public function testNotFound()
    {
        $result = (new DenyWordService())->get();
        $this->assertEmpty($result);
    }

    public function testGet()
    {
        DenyWord::factory()->count(10)->create();

        $result = (new DenyWordService())->get();

        $this->assertCount(10, $result);
    }
}
