<?php

namespace App\Repositories\v1;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository
{
    public function allForUser(int $userId): Collection
    {
        return Media::where('user_id', $userId)->latest()->get();
    }

    public function all(): Collection
    {
        return Media::with('user:id,name,email')->latest()->get();
    }

    public function create(array $data): Media
    {
        return Media::create($data);
    }

    public function delete(Media $media): void
    {
        $media->delete();
    }
}
