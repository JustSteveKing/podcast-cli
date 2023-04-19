<?php

declare(strict_types=1);

namespace App\Commands\Podcasts;

use App\Models\Episode;
use App\Models\Podcast;
use App\Repositories\PodcastRepository;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

final class ListEpisodes extends Command
{
    protected $signature = 'episodes';

    protected $description = 'Display a list of Episodes for a Podcast.';

    public function handle(PodcastRepository $repository): int
    {
        $podcasts = $repository->list();

        $podcast = $this->components->choice(
            question: 'Which Podcasts do you want to display the episodes for?',
            choices: $podcasts->map(fn (Podcast $podcast): string => $podcast->title)->toArray(),
        );

        $podcast = $repository->fromTitle(
            title: $podcast,
        );

        $this->components->info(
            string: "Fetching a list of Episodes.",
        );

        $episodes = $repository->episodes(
            podcast: $podcast->getKey(),
        );

        /**
         * @var Episode $episode
         */
        foreach ($episodes as $episode) {
            $this->components->twoColumnDetail(
                first: Str::limit(
                    value: $episode->getAttribute(
                        key: 'title',
                    ),
                    limit: 30,
                ),
                second: $episode->getAttribute(
                    key: 'published_at',
                )->diffForHumans(),
            );
        }

        return Command::SUCCESS;
    }
}
