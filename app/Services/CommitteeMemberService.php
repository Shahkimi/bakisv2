<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CommitteeMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final readonly class CommitteeMemberService
{
    private const DISK = 'public';

    private const DIRECTORY = 'committee';

    public function create(array $data, ?UploadedFile $photo): CommitteeMember
    {
        if ($photo !== null) {
            $data['photo_path'] = $this->storePhoto($photo);
        }

        $data['sort_order'] = (int) CommitteeMember::query()->max('sort_order') + 1;

        return CommitteeMember::create($data);
    }

    public function update(CommitteeMember $member, array $data, ?UploadedFile $photo = null): CommitteeMember
    {
        if ($photo !== null) {
            $this->deletePhoto($member->photo_path);
            $data['photo_path'] = $this->storePhoto($photo);
        }

        $member->update($data);

        return $member;
    }

    public function delete(CommitteeMember $member): void
    {
        $this->deletePhoto($member->photo_path);
        $member->delete();
    }

    /**
     * @param  array<int, int|string>  $orderedIds
     */
    public function reorder(array $orderedIds): void
    {
        DB::transaction(function () use ($orderedIds): void {
            foreach (array_values($orderedIds) as $index => $id) {
                CommitteeMember::query()->whereKey((int) $id)->update(['sort_order' => $index + 1]);
            }
        });
    }

    /** @return Collection<int, CommitteeMember> */
    public function listOrdered(bool $onlyActive = false): Collection
    {
        $query = CommitteeMember::query()->orderBy('sort_order')->orderBy('id');

        if ($onlyActive) {
            $query->where('is_active', true);
        }

        return $query->get();
    }

    private function storePhoto(UploadedFile $file): string
    {
        $name = now()->format('Ymd_His').'_'.substr(md5((string) microtime(true)), 0, 8).'.'.$file->getClientOriginalExtension();

        return $file->storeAs(self::DIRECTORY, $name, self::DISK);
    }

    private function deletePhoto(?string $path): void
    {
        if ($path !== null && $path !== '' && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}
