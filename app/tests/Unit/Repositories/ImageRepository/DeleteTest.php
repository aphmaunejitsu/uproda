<?php

namespace Tests\Unit\Repositories\ImageRepository;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Image;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ImageRepository;
use Illuminate\Support\Carbon;

/**
 * @group api/v1/image/delete
 * @group ImageRepository
 * @group DeleteImage
 * @group DeleteImageTest
 */
class DeleteTest extends TestCase
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
    public function testFailDelete()
    {
        $image = $this->repo->deleteByBasename('abc', 'password');
        $this->assertNull($image);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFailDeleteWrongPassword()
    {
        Image::factory()
            ->has(Comment::factory()->count(10))
            ->create([
                'basename' => 'xyz',
                'delkey'   => 'abc'
            ]);
        $image = $this->repo->deleteByBasename('xyz', 'colibri');
        $this->assertNull($image);
    }

    /**
     * @dataProvider successDleteProvider
     */
    public function testSuccessDelete($basename, $delkey, $password)
    {
        $data = Image::factory()
            ->has(Comment::factory()->count(10))
            ->create([
                'basename' => $basename,
                'delkey'   => $delkey
            ]);

        Carbon::setTestNow(new Carbon('2021-04-25 00:01:02'));

        $image = $this->repo->deleteByBasename($basename, $password);
        $comments = Comment::withTrashed()->where('image_id', $image->id)->first();
        $this->assertInstanceOf(Image::class, $image);
        $this->assertEquals('2021-04-25 00:01:02', $image->deleted_at);
        $this->assertEquals('2021-04-25 00:01:02', $comments->deleted_at);
    }

    public static function successDleteProvider()
    {
        return [
            ['xyz', 'abc',  'abc'],
            ['abc',  null,  'example'],
            ['def', 'aaa',  'example']
        ];
    }
}
