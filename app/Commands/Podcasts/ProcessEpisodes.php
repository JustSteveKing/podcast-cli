<?php

declare(strict_types=1);

namespace App\Commands\Podcasts;

use App\Contracts\Services\PodcastServiceContract;
use App\Models\Podcast;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Throwable;

final class ProcessEpisodes extends Command
{
    protected $signature = 'process {url : The URL for the Podcast Feed.}';

    protected $description = 'Process the Podcast Feed URL to import the Episodes.';

    public function handle(PodcastServiceContract $service): int
    {
        $url = $this->argument(
            key: 'url',
        );

        $podcast = Podcast::query()->where('feed_url', $url)->first();

        if (! $podcast) {
            $this->components->error(
                string: 'There is no podcast for this feed yet, please run the import command first.',
            );

            return Command::INVALID;
        }

        try {
            $this->components->info(
                string: 'Fetching Podcast Feed from URL.',
            );

            $feedContents = $service->content(
                url: $url,
            );
        } catch (Throwable $exception) {
            $this->components->error(
                string: $exception->getMessage(),
            );

            return Command::FAILURE;
        }

        $this->components->info(
            string: 'Parsing the Podcast Feed.',
        );
        $channel = $service->feed(
            xml: $feedContents,
        );

        foreach ($channel as $value)
        {
            $episode = $service->episode(
                feed: [
                    'title' => $value->getTitle(),
                    'link' => $value->getLink(),
                    'description' => $value->getDescription(),
                    'media' => $value->getEnclosure(),
                    'published_at' => $value->getDateModified()->format('Y-m-d H:i:s'),
                ],
            );

            $service->relate(
                podcast: $podcast->getKey(),
                episode: $episode,
            );
        }

        return Command::SUCCESS;
    }
}
