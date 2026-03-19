<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final class SecureFileUpload implements ValidationRule
{
    private const MaxPdfPages = 10;

    private const MinImageSize = 50;

    private const MaxImageSize = 10000;

    /**
     * @param  Closure(string):void  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('Invalid file upload.');

            return;
        }

        $clientName = (string) $value->getClientOriginalName();
        if ($clientName === '' || str_contains($clientName, "\0")) {
            $fail('Invalid file name.');

            return;
        }

        $ext = strtolower((string) $value->getClientOriginalExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        if (! in_array($ext, $allowed, true)) {
            $fail('Invalid file type.');

            return;
        }

        // Block double-extension shells (e.g., shell.php.jpg).
        $lowerName = strtolower($clientName);
        $parts = explode('.', $lowerName);
        if (count($parts) >= 3) {
            $dangerousSegments = ['php', 'phtml', 'phar', 'cgi', 'sh', 'bash', 'exe', 'js', 'html', 'htm', 'svg', 'xml', 'json'];
            foreach (array_slice($parts, 0, -1) as $segment) {
                if (in_array($segment, $dangerousSegments, true)) {
                    $fail('Suspicious file name detected.');

                    return;
                }
            }
        }

        $realPath = $value->getRealPath();
        if (! is_string($realPath) || ! is_readable($realPath)) {
            $fail('File is not readable.');

            return;
        }

        $header = file_get_contents($realPath, false, null, 0, 16);
        if (! is_string($header) || $header === '') {
            $fail('Invalid file content.');

            return;
        }

        if ($ext === 'png') {
            if (! str_starts_with($header, "\x89PNG\x0D\x0A\x1A\x0A")) {
                $fail('PNG signature mismatch.');

                return;
            }

            // Enforce dimensions for PNG uploads too.
            $this->validateImageDimensions($realPath, $fail);
        } elseif ($ext === 'pdf') {
            if (! str_starts_with($header, '%PDF-')) {
                $fail('PDF signature mismatch.');

                return;
            }

            $this->validatePdf($realPath, $fail);

            return;
        } else {
            // JPEG/JPG magic bytes: FF D8 FF (SOI + start marker)
            if (! str_starts_with($header, "\xFF\xD8\xFF")) {
                $fail('JPEG signature mismatch.');

                return;
            }

            $this->validateImageDimensions($realPath, $fail);
        }

        $mime = $this->detectMimeType($realPath);
        $mimeAllowed = match ($ext) {
            'jpg', 'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'pdf' => ['application/pdf'],
            default => [],
        };

        if ($mime === null || ! in_array($mime, $mimeAllowed, true)) {
            $fail('File MIME type mismatch.');

            return;
        }
    }

    /**
     * @param  Closure(string):void  $fail
     */
    private function validatePdf(string $realPath, Closure $fail): void
    {
        $content = file_get_contents($realPath);
        if (! is_string($content)) {
            $fail('Invalid PDF file.');

            return;
        }

        if (preg_match('/<\?php/i', $content) === 1) {
            $fail('Invalid PDF file.');

            return;
        }

        // Basic active content checks.
        if (preg_match('/\/(JavaScript|JS)\b/i', $content) === 1) {
            $fail('Suspicious PDF content detected.');

            return;
        }

        $matches = [];
        preg_match_all('/\/Type\s*\/Page\b/i', $content, $matches);
        $pageCount = isset($matches[0]) ? count($matches[0]) : 0;

        if ($pageCount < 1 || $pageCount > self::MaxPdfPages) {
            $fail('PDF page count is not allowed.');
        }
    }

    /**
     * @param  Closure(string):void  $fail
     */
    private function validateImageDimensions(string $realPath, Closure $fail): void
    {
        $info = @getimagesize($realPath);
        if (! is_array($info) || ! isset($info[0], $info[1])) {
            $fail('Invalid image file.');

            return;
        }

        $width = (int) $info[0];
        $height = (int) $info[1];

        if ($width < self::MinImageSize || $height < self::MinImageSize) {
            $fail('Image dimensions are not allowed.');

            return;
        }

        if ($width > self::MaxImageSize || $height > self::MaxImageSize) {
            $fail('Image dimensions are not allowed.');
        }
    }

    private function detectMimeType(string $realPath): ?string
    {
        $finfo = new \finfo(\FILEINFO_MIME_TYPE);
        $mime = $finfo->file($realPath);
        if (! is_string($mime) || $mime === '') {
            return null;
        }

        return strtolower($mime);
    }
}
