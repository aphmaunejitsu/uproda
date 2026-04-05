<?php

namespace Tests\Unit\Repositories;

use App\Models\Image as ModelsImage;
use App\Repositories\FileRepository;
use App\Repositories\FileRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileRepositoryTest extends TestCase
{
    private FileRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->repository = new FileRepository();
    }

    public function testConvertDecimalToDMS(): void
    {
        $result = $this->repository->convertDecimalToDMS(35.630152);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);

        // degrees
        $this->assertEquals(35, $result[0][0]);
        $this->assertEquals(1, $result[0][1]);

        // minutes
        $this->assertEquals(37, $result[1][0]);
        $this->assertEquals(1, $result[1][1]);

        // seconds: fractional part
        $this->assertEquals(100, $result[2][1]);
        $this->assertIsNumeric($result[2][0]);
    }

    public function testConvertDecimalToDMSNegative(): void
    {
        $result = $this->repository->convertDecimalToDMS(-139.74);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);

        // Should use absolute value
        $this->assertEquals(139, $result[0][0]);
        $this->assertEquals(44, $result[1][0]);
        $this->assertEquals(100, $result[2][1]);
    }

    public function testConvertDecimalToDMSOutOfRange(): void
    {
        $this->assertFalse($this->repository->convertDecimalToDMS(181));
        $this->assertFalse($this->repository->convertDecimalToDMS(-181));
    }

    public function testConvertDecimalToDMSZero(): void
    {
        $result = $this->repository->convertDecimalToDMS(0);

        $this->assertEquals([[0, 1], [0, 1], [0, 100]], $result);
    }

    public function testDeleteByImage(): void
    {
        Storage::fake('image');

        $image = new ModelsImage();
        $image->basename = 'Abc';
        $image->ext = 'png';
        $image->t_ext = 'jpg';

        Storage::disk('image')->put('/a/Abc.png', 'image content');
        Storage::disk('image')->put('/a/thumbnail/Abc.jpg', 'thumb content');

        $this->repository->deleteByImage($image);

        Storage::disk('image')->assertMissing('/a/Abc.png');
        Storage::disk('image')->assertMissing('/a/thumbnail/Abc.jpg');
    }

    public function testSaveUploadImage(): void
    {
        Storage::fake('image');

        $tmpFile = tempnam(sys_get_temp_dir(), 'upload_');
        file_put_contents($tmpFile, 'fake image data');

        $this->repository->saveUploadImage($tmpFile, 'Abc', 'png');

        Storage::disk('image')->assertExists('/a/Abc.png');

        unlink($tmpFile);
    }

    public function testDeleteTmpFiles(): void
    {
        Storage::fake('tmp');

        // Create files in the fake storage
        Storage::disk('tmp')->put('old_file.txt', 'old content');
        Storage::disk('tmp')->put('new_file.txt', 'new content');

        // We need to mock lastModified since fake storage doesn't support time manipulation easily
        $storageMock = Storage::partialMock();

        $storageMock->shouldReceive('disk')
            ->with('tmp')
            ->andReturnSelf();

        $storageMock->shouldReceive('allFiles')
            ->andReturn(['old_file.txt', 'new_file.txt']);

        $storageMock->shouldReceive('lastModified')
            ->with('old_file.txt')
            ->andReturn(now()->subMinutes(120)->getTimestamp());

        $storageMock->shouldReceive('lastModified')
            ->with('new_file.txt')
            ->andReturn(now()->getTimestamp());

        $storageMock->shouldReceive('delete')
            ->with('old_file.txt')
            ->once()
            ->andReturn(true);

        $this->repository->deleteTmpFiles(60, 'tmp');
    }
}
