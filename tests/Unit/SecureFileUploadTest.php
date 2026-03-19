<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Rules\SecureFileUpload;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;
use Tests\Support\FileTestHelper;

final class SecureFileUploadTest extends TestCase
{
    private SecureFileUpload $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new SecureFileUpload;
    }

    private function validateAndGetFailureMessage(UploadedFile $file): ?string
    {
        $failureMessage = null;

        $this->rule->validate(
            'bukti_bayaran',
            $file,
            function (string $message) use (&$failureMessage): void {
                $failureMessage = $message;
            },
        );

        return $failureMessage;
    }

    private function assertUploadPasses(UploadedFile $file): void
    {
        $failureMessage = $this->validateAndGetFailureMessage($file);
        $this->assertNull($failureMessage, $failureMessage ?? 'Validation should pass.');
    }

    private function assertUploadFails(UploadedFile $file, string $expectedMessageFragment): void
    {
        $failureMessage = $this->validateAndGetFailureMessage($file);
        $this->assertNotNull($failureMessage, 'Validation should fail but passed.');
        $this->assertStringContainsString($expectedMessageFragment, $failureMessage ?? '');
    }

    public function test_accepts_single_page_pdf(): void
    {
        $file = FileTestHelper::createValidPdf(1, 'receipt-1page.pdf');
        $this->assertUploadPasses($file);
    }

    public function test_accepts_multi_page_pdf_within_limit(): void
    {
        $file = FileTestHelper::createValidPdf(5, 'receipt-5pages.pdf');
        $this->assertUploadPasses($file);
    }

    public function test_accepts_maximum_page_pdf(): void
    {
        $file = FileTestHelper::createValidPdf(10, 'receipt-10pages.pdf');
        $this->assertUploadPasses($file);
    }

    public function test_accepts_small_jpeg_at_minimum_dimensions(): void
    {
        $file = FileTestHelper::createValidJpeg(50, 50, 'photo-small.jpg');
        $this->assertUploadPasses($file);
    }

    public function test_accepts_standard_size_jpeg(): void
    {
        $file = FileTestHelper::createValidJpeg(1000, 1000, 'photo-standard.jpg');
        $this->assertUploadPasses($file);
    }

    public function test_accepts_standard_size_png(): void
    {
        $file = FileTestHelper::createValidPng(500, 500, 'screenshot.png');
        $this->assertUploadPasses($file);
    }

    public function test_accepts_high_resolution_image(): void
    {
        // Keep dimensions high enough to validate the upper-bound logic,
        // without creating an overly memory-intensive bitmap.
        $file = FileTestHelper::createValidJpeg(3000, 3000, 'scan-highres.jpg');
        $this->assertUploadPasses($file);
    }

    public function test_rejects_php_file_with_jpg_extension(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'phpjpg_');
        if ($tmp === false) {
            $this->fail('Unable to create temp file.');
        }

        file_put_contents($tmp, "<?php echo 'pwnd'; ?>");

        $file = FileTestHelper::createUploadedFile($tmp, 'evil.jpg', 'image/jpeg');
        $this->assertUploadFails($file, 'JPEG signature mismatch');
    }

    public function test_rejects_double_extension(): void
    {
        $file = FileTestHelper::createFileWithWrongMagicBytes('jpg', 'shell.php.jpg');
        $this->assertUploadFails($file, 'Suspicious file name detected');
    }

    public function test_rejects_null_byte_in_filename(): void
    {
        $valid = FileTestHelper::createValidJpeg(50, 50, 'photo.jpg');
        $path = (string) $valid->getRealPath();

        $badOriginalClientName = 'receipt.pdf'.chr(0).'.jpg';
        $file = new UploadedFile($path, $badOriginalClientName, 'image/jpeg', 0, true);

        $this->assertUploadFails($file, 'Invalid file name');
    }

    public function test_rejects_pdf_with_embedded_php(): void
    {
        $file = FileTestHelper::createPdfWithEmbeddedPhp('malicious.pdf');
        $this->assertUploadFails($file, 'Invalid PDF file');
    }

    public function test_rejects_pdf_with_javascript(): void
    {
        $file = FileTestHelper::createPdfWithJavaScript('malicious.pdf');
        $this->assertUploadFails($file, 'Suspicious PDF content');
    }

    public function test_rejects_pdf_exceeding_page_limit(): void
    {
        $file = FileTestHelper::createValidPdf(11, 'receipt-11pages.pdf');
        $this->assertUploadFails($file, 'PDF page count is not allowed');
    }

    public function test_rejects_image_below_minimum_dimensions(): void
    {
        $file = FileTestHelper::createValidPng(20, 20, 'tiny.png');
        $this->assertUploadFails($file, 'Image dimensions are not allowed');
    }

    public function test_rejects_image_exceeding_maximum_dimensions(): void
    {
        // 10001x50 is just over the 10000 width limit while keeping the image small enough for tests.
        $file = FileTestHelper::createValidPng(10001, 50, 'huge.png');
        $this->assertUploadFails($file, 'Image dimensions are not allowed');
    }

    public function test_rejects_jpeg_with_wrong_magic_bytes(): void
    {
        $file = FileTestHelper::createFileWithWrongMagicBytes('jpg', 'fake-jpeg.jpg');
        $this->assertUploadFails($file, 'JPEG signature mismatch');
    }

    public function test_rejects_png_with_wrong_magic_bytes(): void
    {
        $file = FileTestHelper::createFileWithWrongMagicBytes('png', 'fake-png.png');
        $this->assertUploadFails($file, 'PNG signature mismatch');
    }

    public function test_rejects_pdf_with_wrong_magic_bytes(): void
    {
        $file = FileTestHelper::createFileWithWrongMagicBytes('pdf', 'fake-pdf.pdf');
        $this->assertUploadFails($file, 'PDF signature mismatch');
    }

    public function test_rejects_pdf_with_zero_pages(): void
    {
        $file = FileTestHelper::createValidPdf(0, 'receipt-0pages.pdf');
        $this->assertUploadFails($file, 'PDF page count is not allowed');
    }

    public function test_rejects_empty_file(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'empty_');
        if ($tmp === false) {
            $this->fail('Unable to create temp file.');
        }

        file_put_contents($tmp, '');

        $file = FileTestHelper::createUploadedFile($tmp, 'empty.pdf', 'application/pdf');
        $this->assertUploadFails($file, 'Invalid file content');
    }

    public function test_rejects_corrupted_image(): void
    {
        $file = FileTestHelper::createCorruptedImage('png', 'corrupt.png');
        $this->assertUploadFails($file, 'signature mismatch');
    }
}
