<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'path',
        'mime_type',
        'size',
    ];

    protected $appends = ['full_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullUrlAttribute(): ?string
    {
        if (! $this->path) {
            return null;
        }

        return asset('storage/'.$this->path);
    }
}
