<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Http\UploadedFile;

final class FileTestHelper
{
    public static function createUploadedFile(string $absolutePath, string $originalClientName, ?string $mimeType = null): UploadedFile
    {
        return new UploadedFile(
            $absolutePath,
            $originalClientName,
            $mimeType,
            0,
            true, // test mode: local files are used
        );
    }

    public static function createValidJpeg(int $width, int $height, string $name = 'photo.jpg'): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'jpeg_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        $pngLike = self::renderJpegBytes($tmp, $width, $height);

        // Ensure correct extension and client name; validation uses original extension.
        $originalClientName = $name;

        return self::createUploadedFile($pngLike, $originalClientName, 'image/jpeg');
    }

    public static function createValidPng(int $width, int $height, string $name = 'screenshot.png'): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'png_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        $pngLike = self::renderPngBytes($tmp, $width, $height);

        $originalClientName = $name;

        return self::createUploadedFile($pngLike, $originalClientName, 'image/png');
    }

    /**
     * Create a minimal PDF that satisfies SecureFileUpload's checks:
     * - header contains "%PDF-"
     * - contains "/Type /Page" occurrences matching $pageCount
     * - does not contain "<?php" or "/JavaScript" (JavaScript action)
     */
    public static function createValidPdf(int $pageCount, string $name = 'receipt.pdf'): UploadedFile
    {
        if ($pageCount < 1) {
            $pageCount = 0;
        }

        $tmp = tempnam(sys_get_temp_dir(), 'pdf_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        $content = self::renderPdfBytes($pageCount);
        file_put_contents($tmp, $content, LOCK_EX);

        return self::createUploadedFile($tmp, $name, 'application/pdf');
    }

    public static function createCorruptedImage(string $extension, string $originalClientName): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'img_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        file_put_contents($tmp, 'not-an-image');

        $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';

        return self::createUploadedFile($tmp, $originalClientName, $mimeType);
    }

    public static function createPdfWithEmbeddedPhp(string $originalClientName = 'malicious.pdf'): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'pdf_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        $content = self::renderPdfBytes(1)."\n<?php echo 'pwnd'; ?>\n";
        file_put_contents($tmp, $content, LOCK_EX);

        return self::createUploadedFile($tmp, $originalClientName, 'application/pdf');
    }

    public static function createPdfWithJavaScript(string $originalClientName = 'malicious.pdf'): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'pdf_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        $content = self::renderPdfBytes(1)."\n/JavaScript << >>\n";
        file_put_contents($tmp, $content, LOCK_EX);

        return self::createUploadedFile($tmp, $originalClientName, 'application/pdf');
    }

    public static function createPdfWithPageCount(int $pageCount, string $originalClientName = 'receipt.pdf'): UploadedFile
    {
        return self::createValidPdf($pageCount, $originalClientName);
    }

    public static function createFileWithWrongMagicBytes(string $extension, string $originalClientName): UploadedFile
    {
        $tmp = tempnam(sys_get_temp_dir(), 'bad_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temp file.');
        }

        file_put_contents($tmp, 'FAKE-CONTENT-WRONG-SIGNATURE');

        $mimeType = $extension === 'png' ? 'image/png' : ($extension === 'jpg' ? 'image/jpeg' : 'application/pdf');

        return self::createUploadedFile($tmp, $originalClientName, $mimeType);
    }

    /**
     * @return string path to the rendered file (temp file path)
     */
    private static function renderJpegBytes(string $tmpPath, int $width, int $height): string
    {
        $img = \imagecreatetruecolor($width, $height);
        if ($img === false) {
            // As a fallback, write an invalid image; test expecting invalid will still work.
            file_put_contents($tmpPath, 'FAKE-JPEG');

            return $tmpPath;
        }

        // Fill with a deterministic color.
        $bg = \imagecolorallocate($img, 20, 80, 160);
        \imagefilledrectangle($img, 0, 0, $width, $height, $bg);

        \imagejpeg($img, $tmpPath, 85);
        \imagedestroy($img);

        return $tmpPath;
    }

    /**
     * @return string path to the rendered file (temp file path)
     */
    private static function renderPngBytes(string $tmpPath, int $width, int $height): string
    {
        $img = \imagecreatetruecolor($width, $height);
        if ($img === false) {
            file_put_contents($tmpPath, 'FAKE-PNG');

            return $tmpPath;
        }

        $bg = \imagecolorallocate($img, 20, 80, 160);
        \imagefilledrectangle($img, 0, 0, $width, $height, $bg);

        \imagepng($img, $tmpPath);
        \imagedestroy($img);

        return $tmpPath;
    }

    private static function renderPdfBytes(int $pageCount): string
    {
        // We generate a minimal "valid enough" PDF for MIME detection.
        // SecureFileUpload validates:
        // - the first bytes start with "%PDF-"
        // - occurrences of "/Type /Page" (page count) via regex
        // - absence of "<?php" and "/JavaScript"/"JS" patterns
        if ($pageCount < 0) {
            $pageCount = 0;
        }

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";

        $objects = [];
        $objects[1] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        $kids = [];
        for ($i = 0; $i < $pageCount; $i++) {
            $kids[] = (string) (4 + $i).' 0 R';
        }
        $kidsString = '['.implode(' ', $kids).']';

        $objects[2] = "2 0 obj\n<< /Type /Pages /Kids $kidsString /Count $pageCount >>\nendobj\n";

        $fontId = 3;
        $objects[$fontId] = $fontId." 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        $pageStartId = 4;
        $contentStartId = $pageStartId + $pageCount;

        $maxId = $contentStartId + $pageCount - 1;
        if ($pageCount === 0) {
            $maxId = $fontId; // Only objects 1..3 exist.
        }

        for ($i = 0; $i < $pageCount; $i++) {
            $pageId = $pageStartId + $i;
            $contentsId = $contentStartId + $i;

            // Keep content simple and safe. Do not include "<?php" or "/JavaScript".
            $stream = 'BT /F1 12 Tf 72 720 Td (Page '.($i + 1).') Tj ET';
            $len = strlen($stream);

            $objects[$contentsId] = $contentsId." 0 obj\n<< /Length {$len} >>\nstream\n"
                .$stream."\nendstream\nendobj\n";

            $objects[$pageId] = $pageId." 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] "
                ."/Resources << /Font << /F1 {$fontId} 0 R >> >> /Contents {$contentsId} 0 R >>\nendobj\n";
        }

        $offsets = [];
        $offsets[0] = 0;

        // Serialize objects while tracking byte offsets.
        for ($id = 1; $id <= $maxId; $id++) {
            $offsets[$id] = strlen($pdf);
            $pdf .= $objects[$id] ?? '';
        }

        $xrefOffset = strlen($pdf);
        $xref = "xref\n0 ".($maxId + 1)."\n";
        $xref .= "0000000000 65535 f \n";
        for ($id = 1; $id <= $maxId; $id++) {
            $offset = $offsets[$id] ?? 0;
            $xref .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT)." 00000 n \n";
        }

        $trailer = "trailer\n<< /Size ".($maxId + 1)." /Root 1 0 R >>\n"
            ."startxref\n".$xrefOffset."\n%%EOF\n";

        return $pdf.$xref.$trailer;
    }
}
