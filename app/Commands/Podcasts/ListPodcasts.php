<?php

declare(strict_types=1);

namespace App\Commands\Podcasts;

use App\Models\Podcast;
use App\Repositories\PodcastRepository;
use LaravelZero\Framework\Commands\Command;

final class ListPodcasts extends Command
{
    protected $signature = 'podcasts';

    protected $description = 'Display a list of Podcasts you have subscribed to.';

    public function handle(PodcastRepository $repository): int
    {
        $this->components->info(
            string: 'Fetching a list of Podcasts.',
        );

        $podcasts = $repository->list();

        if (! $podcasts->count()) {
            $this->components->error(
                string: 'You have not subscribed to any Podcasts.',
            );

            return Command::SUCCESS;
        }

        /**
         * @var Podcast $podcast
         */
        foreach ($podcasts as $podcast) {
            $this->components->twoColumnDetail(
                first: $podcast->getAttribute(
                    key: 'title',
                ),
                second: $podcast->getAttribute(
                    key: 'copyright',
                ),
            );
        }

        return Command::SUCCESS;
    }
}
