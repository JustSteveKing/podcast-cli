<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'title',
        'link',
        'description',
        'media',
        'listened',
        'podcast_id',
        'published_at',
    ];

    protected $casts = [
        'media' => AsArrayObject::class,
        'listened' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(
            related: Podcast::class,
            foreignKey: 'podcast_id',
        );
    }
}
