<?php

namespace Tests\Unit\Services\ImageService;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use App\Repositories\ImageRepositoryInterface;
use App\Services\ImageService;
use App\Exceptions\ImageServiceException;

/**
 * @group Image::SetNG
 * @group ImageService
 * @group SetNgTest
 */
class SetNgTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * A basic unit test example.
     */
    public function testSetNgNotFound()
    {
        $this->mock(
            ImageRepositoryInterface::class,
            function (MockInterface $m) {
                $m->shouldReceive('setNgByBasename')->andReturn(null);
            }
        );

        $this->expectException(ImageServiceException::class);
        $this->expectExceptionCode(10000);
        $this->expectExceptionMessage('NGに設定できませんでした');

        (new ImageService)->setNg('aaa');
    }
}
