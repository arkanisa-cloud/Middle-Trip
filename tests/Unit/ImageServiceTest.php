<?php

namespace Tests\Unit;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    public function test_image_service_converts_uploaded_image_to_webp(): void
    {
        Storage::fake('public');

        // Create a fake image (e.g. 800x600 PNG)
        $file = UploadedFile::fake()->image('test-photo.png', 800, 600);

        $service = new ImageService();
        $storedPath = $service->storeAsWebp($file, 'mountains', 'public');

        // Path should end in .webp
        $this->assertStringEndsWith('.webp', $storedPath);

        // File should exist in fake storage
        Storage::disk('public')->assertExists($storedPath);

        // Verify the stored file content is a valid WebP image
        $content = Storage::disk('public')->get($storedPath);
        $this->assertNotEmpty($content);

        // Header check for RIFF....WEBP
        $this->assertStringStartsWith('RIFF', substr($content, 0, 4));
        $this->assertEquals('WEBP', substr($content, 8, 4));
    }

    public function test_image_service_resizes_large_images(): void
    {
        Storage::fake('public');

        // Create a fake image larger than maxWidth (e.g. 2400x1200)
        $file = UploadedFile::fake()->image('giant-landscape.jpg', 2400, 1200);

        $service = new ImageService();
        $storedPath = $service->storeAsWebp($file, 'mountains', 'public', 80, 1200);

        Storage::disk('public')->assertExists($storedPath);
        $content = Storage::disk('public')->get($storedPath);

        $image = imagecreatefromstring($content);
        $this->assertNotFalse($image);
        $this->assertLessThanOrEqual(1200, imagesx($image));
        imagedestroy($image);
    }
}
