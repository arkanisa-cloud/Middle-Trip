<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Konversi dan simpan file gambar yang diunggah ke format WebP.
     *
     * @param UploadedFile $file File gambar yang diunggah
     * @param string $directory Direktori penyimpanan (misal: 'mountains', 'avatars')
     * @param string $disk Disk storage yang digunakan (default: 'public')
     * @param int $quality Kualitas kompresi WebP (0-100, default: 82)
     * @param int|null $maxWidth Lebar maksimal gambar dalam pixel (default: 1920)
     * @return string Path relatif file yang disimpan di storage
     */
    public function storeAsWebp(
        UploadedFile $file,
        string $directory = 'images',
        string $disk = 'public',
        int $quality = 82,
        ?int $maxWidth = 1920
    ): string {
        $realPath = $file->getRealPath();

        if (! $realPath || ! file_exists($realPath)) {
            return $file->store($directory, $disk);
        }

        // Coba buka gambar menggunakan GD
        $image = @imagecreatefromstring((string) file_get_contents($realPath));

        if (! $image) {
            // Jika GD gagal membuka, fallback ke penyimpanan standar Laravel
            return $file->store($directory, $disk);
        }

        // Tangani rotasi EXIF otomatis (terutama untuk foto dari kamera smartphone)
        $image = $this->fixExifOrientation($realPath, $image);

        // Pertahankan transparansi (PNG, WebP, GIF)
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Resize proporsional jika melebihi maxWidth
        if ($maxWidth !== null) {
            $origWidth = imagesx($image);
            $origHeight = imagesy($image);

            if ($origWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) round(($origHeight / $origWidth) * $newWidth);

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagedestroy($image);
                $image = $resized;
            }
        }

        // Tangkap output WebP dari buffer
        ob_start();
        imagewebp($image, null, $quality);
        $webpContents = ob_get_clean();
        imagedestroy($image);

        if (! $webpContents) {
            return $file->store($directory, $disk);
        }

        $filename = Str::random(40) . '.webp';
        $fullPath = trim($directory, '/') . '/' . $filename;

        Storage::disk($disk)->put($fullPath, $webpContents);

        return $fullPath;
    }

    /**
     * Memperbaiki orientasi gambar berdasarkan metadata EXIF jika ada.
     *
     * @param string $path
     * @param \GdImage $image
     * @return \GdImage
     */
    private function fixExifOrientation(string $path, \GdImage $image): \GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        try {
            $exif = @exif_read_data($path);
            if (! empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($image, 180, 0);
                        if ($rotated) {
                            imagedestroy($image);
                            return $rotated;
                        }
                        break;
                    case 6:
                        $rotated = imagerotate($image, -90, 0);
                        if ($rotated) {
                            imagedestroy($image);
                            return $rotated;
                        }
                        break;
                    case 8:
                        $rotated = imagerotate($image, 90, 0);
                        if ($rotated) {
                            imagedestroy($image);
                            return $rotated;
                        }
                        break;
                }
            }
        } catch (\Throwable) {
            // Abaikan jika metadata EXIF tidak dapat dibaca
        }

        return $image;
    }
}
