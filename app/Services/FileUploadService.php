<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final readonly class FileUploadService
{
    public function uploadMemberPhoto(UploadedFile $file, string $noKp): string
    {
        $disk = Storage::disk('public');
        $directory = 'members/photos';
        $disk->makeDirectory($directory);

        $safe = preg_replace('/\D/', '', $noKp) ?: 'photo';
        $filename = $safe.'_'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');

        return basename((string) $path);
    }

    public function uploadPaymentProof(UploadedFile $file, int $paymentId): string
    {
        $disk = Storage::disk('local');
        $directory = 'members/payments';
        $disk->makeDirectory($directory);

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = 'payment_'.$paymentId.'_'.bin2hex(random_bytes(16)).'.'.$extension;
        $path = $file->storeAs($directory, $filename);

        $storedPath = $disk->path($path);
        $this->stripImageMetadata($storedPath, $extension);

        // Best-effort permissions hardening.
        try {
            @chmod($storedPath, 0644);
        } catch (\Throwable) {
            // Ignore chmod failures (e.g. platform-specific permission handling).
        }

        return basename((string) $path);
    }

    private function stripImageMetadata(string $storedPath, string $extension): void
    {
        if (! is_file($storedPath)) {
            return;
        }

        if ($extension !== 'jpg' && $extension !== 'jpeg' && $extension !== 'png') {
            return;
        }

        $info = @getimagesize($storedPath);
        if (! is_array($info)) {
            return;
        }

        if ($extension === 'png') {
            $image = @imagecreatefrompng($storedPath);
            if (! is_resource($image) && ! $image instanceof \GdImage) {
                return;
            }

            @imagepng($image, $storedPath);
            @imagedestroy($image);

            return;
        }

        // Treat jpeg/jpg the same way for re-encoding.
        $image = @imagecreatefromjpeg($storedPath);
        if (! is_resource($image) && ! $image instanceof \GdImage) {
            return;
        }

        @imagejpeg($image, $storedPath, 85);
        @imagedestroy($image);
    }
}
