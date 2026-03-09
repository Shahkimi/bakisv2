<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;

final readonly class FileUploadService
{
    public function uploadMemberPhoto(UploadedFile $file, string $noKp): string
    {
        $safe = preg_replace('/\D/', '', $noKp) ?: 'photo';
        $filename = $safe.'_'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('public/members/photos', $filename);

        return basename((string) $path);
    }

    public function uploadPaymentProof(UploadedFile $file, int $paymentId): string
    {
        $filename = 'payment_'.$paymentId.'_'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('public/members/payments', $filename);

        return basename((string) $path);
    }
}
