<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Perlembagaan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final readonly class PerlembagaanService
{
    private const string DISK = 'public';

    private const string DIRECTORY = 'perlembagaan';

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, UploadedFile $file): Perlembagaan
    {
        [$path, $name, $size] = $this->storeFile($file);

        return Perlembagaan::create([
            'tajuk' => $data['tajuk'],
            'file_path' => $path,
            'file_name' => $name,
            'file_size' => $size,
            'is_active' => filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'urutan' => (int) ($data['urutan'] ?? 0),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Perlembagaan $perlembagaan, array $data, ?UploadedFile $file = null): Perlembagaan
    {
        $payload = [
            'tajuk' => $data['tajuk'],
            'is_active' => filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
        ];

        if (array_key_exists('urutan', $data)) {
            $payload['urutan'] = (int) $data['urutan'];
        }

        if ($file !== null) {
            $this->deleteFile($perlembagaan->file_path);
            [$path, $name, $size] = $this->storeFile($file);
            $payload['file_path'] = $path;
            $payload['file_name'] = $name;
            $payload['file_size'] = $size;
        }

        $perlembagaan->update($payload);

        return $perlembagaan;
    }

    public function delete(Perlembagaan $perlembagaan): void
    {
        $this->deleteFile($perlembagaan->file_path);
        $perlembagaan->delete();
    }

    /**
     * @return Collection<int, Perlembagaan>
     */
    public function listOrdered(bool $onlyActive = false): Collection
    {
        $query = Perlembagaan::query()
            ->orderBy('urutan')
            ->orderByDesc('created_at');

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        return $query->get();
    }

    /**
     * @return Collection<int, Perlembagaan>
     */
    public function listForPublic(): Collection
    {
        return $this->listOrdered(onlyActive: true);
    }

    /**
     * Store the uploaded PDF and return [path, originalName, size].
     *
     * @return array{0: string, 1: string, 2: int}
     */
    private function storeFile(UploadedFile $file): array
    {
        $filename = Str::random(40).'.pdf';
        $path = $file->storeAs(self::DIRECTORY, $filename, self::DISK);

        if ($path === false) {
            throw new \RuntimeException('Failed to store perlembagaan file.');
        }

        return [$path, $file->getClientOriginalName(), $file->getSize() ?: 0];
    }

    private function deleteFile(?string $path): void
    {
        if (is_string($path) && $path !== '' && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}
