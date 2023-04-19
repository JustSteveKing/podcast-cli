<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\DataObjects\ObjectContract;
use App\Contracts\Services\PodcastServiceContract;
use App\DataObjects\EpisodeObject;
use App\DataObjects\PodcastObject;
use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laminas\Feed\Reader\Entry\Atom;
use Laminas\Feed\Reader\Feed\Atom as AtomFeed;
use Laminas\Feed\Reader\Feed\FeedInterface;
use Laminas\Feed\Reader\Feed\Rss;
use Laminas\Feed\Reader\Reader;

final class PodcastService implements PodcastServiceContract
{
    /**
     * @param string $url
     * @return string
     * @throws RequestException
     */
    public function content(string $url): string
    {
        return Http::accept(
            contentType: 'application/xml',
        )->get(
            url: $url,
        )->throw()->body();
    }

    /**
     * @param string $xml
     * @return FeedInterface|Rss|Atom|AtomFeed
     */
    public function feed(string $xml): FeedInterface|Rss|Atom|AtomFeed
    {
        return Reader::importString(
            string: $xml,
        );
    }

    /**
     * @param array $feed
     * @return PodcastObject
     */
    public function build(array $feed): PodcastObject
    {
        return PodcastObject::fromArray(
            data: $feed,
        );
    }

    /**
     * @param array $feed
     * @return EpisodeObject
     */
    public function episode(array $feed): EpisodeObject
    {
        return EpisodeObject::fromArray(
            data: $feed,
        );
    }

    /**
     * @param ObjectContract $podcast
     * @return Podcast|Model
     */
    public function save(ObjectContract $podcast): Podcast|Model
    {
        return DB::transaction(
            callback: static fn () => Podcast::query()->create(
                attributes: $podcast->toArray(),
            ),
            attempts: 2,
        );
    }

    /**
     * @param string $podcast
     * @param ObjectContract $episode
     * @return Episode|Model
     */
    public function relate(string $podcast, ObjectContract $episode): Episode|Model
    {
        return DB::transaction(
            callback: static fn () => Episode::query()->create(
                attributes: [
                    ...$episode->toArray(),
                    'podcast_id' => $podcast,
                ],
            ),
            attempts: 2,
        );
    }
}
