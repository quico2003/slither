<?php

namespace App\Services\v1;

use App\Models\Media;
use App\Repositories\v1\MediaRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    public function __construct(
        private MediaRepository $mediaRepository
    ) {
    }

    public function allForUser(int $userId): Collection
    {
        return $this->mediaRepository->allForUser($userId);
    }

    public function allForAdmin(): Collection
    {
        return $this->mediaRepository->all();
    }

    public function store(int $userId, UploadedFile $file): Media
    {
        $path = $file->store("media/{$userId}", 'public');

        return $this->mediaRepository->create([
            'user_id' => $userId,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);
    }

    public function delete(Media $media): void
    {
        Storage::disk('public')->delete($media->path);
        $this->mediaRepository->delete($media);
    }
}
