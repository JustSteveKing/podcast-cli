<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class PodcastRepository
{
    public function list(): Collection
    {
        return Podcast::query()->get();
    }

    public function fromTitle(string $title): Podcast|Model
    {
        return Podcast::query()->where('title', $title)->first();
    }

    public function fromEpisodeTitle(string $title): Episode|Model
    {
        return Episode::query()->where('title', $title)->first();
    }

    public function episodes(string $podcast): Collection
    {
        return Episode::query()->where('podcast_id', $podcast)->get();
    }

    public function markListenedTo(Episode $episode): bool
    {
        return (bool) DB::transaction(
            callback: static fn () => $episode->update(['listened' => true]),
            attempts: 2,
        );
    }
}
