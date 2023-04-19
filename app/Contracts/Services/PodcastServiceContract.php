<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Contracts\DataObjects\ObjectContract;
use App\DataObjects\EpisodeObject;
use App\DataObjects\PodcastObject;
use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\RequestException;
use Laminas\Feed\Reader\Entry\Atom;
use Laminas\Feed\Reader\Feed\Atom as AtomFeed;
use Laminas\Feed\Reader\Feed\FeedInterface;
use Laminas\Feed\Reader\Feed\Rss;

interface PodcastServiceContract
{
    /**
     * @param string $url
     * @return string
     * @throws RequestException
     */
    public function content(string $url): string;

    /**
     * @param string $xml
     * @return FeedInterface|Rss|Atom|AtomFeed
     */
    public function feed(string $xml): FeedInterface|Rss|Atom|AtomFeed;

    /**
     * @param array $feed
     * @return PodcastObject
     */
    public function build(array $feed): PodcastObject;

    /**
     * @param array $feed
     * @return EpisodeObject
     */
    public function episode(array $feed): EpisodeObject;

    /**
     * @param ObjectContract $podcast
     * @return Podcast|Model
     */
    public function save(ObjectContract $podcast): Podcast|Model;

    /**
     * @param string $podcast
     * @param ObjectContract $episode
     * @return Episode|Model
     */
    public function relate(string $podcast, ObjectContract $episode): Episode|Model;
}
