<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Podcast extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'title',
        'generator',
        'copyright',
        'language',
        'link',
        'feed_url',
        'description',
        'image',
        'published_at',
    ];

    protected $casts = [
        'image' => AsArrayObject::class,
        'published_at' => 'datetime',
    ];

    public function episodes(): HasMany
    {
        return $this->hasMany(
            related: Episode::class,
            foreignKey: 'podcast_id',
        );
    }
}
