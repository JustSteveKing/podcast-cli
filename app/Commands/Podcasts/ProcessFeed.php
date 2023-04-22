<?php

declare(strict_types=1);

namespace App\Commands\Podcasts;

use App\Contracts\Services\PodcastServiceContract;
use App\Models\Podcast;
use LaravelZero\Framework\Commands\Command;
use Throwable;

final class ProcessFeed extends Command
{
    protected $signature = 'import';

    protected $description = 'Import a Podcast from its feed URL.';

    public function handle(PodcastServiceContract $service): int
    {
        $url = $this->components->ask(
            question: 'What is the URL for the Podcast Feed?',
        );

        if (empty($url)) {
            $this->components->alert(
                string: 'You need to provide a URL to the XML feed for a podcast to import it.',
            );

            return Command::INVALID;
        }

        if (Podcast::query()->where('feed_url', $url)->exists()) {
            $this->components->alert(
                string: 'You have already added this podcast.',
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

        $podcast = $service->build(
            feed: [
                'title' => $channel->getTitle(),
                'generator' => $channel->getGenerator(),
                'copyright' => $channel->getCopyright(),
                'language' => $channel->getLanguage(),
                'link' => $channel->getLink(),
                'feed_url' => $url,
                'description' => $channel->getDescription(),
                'image' => $channel->getImage(),
                'published_at' => ($channel->getLastBuildDate() ?? null)?->format('Y-m-d H:i:s'),
            ],
        );

        $this->components->info(
            string: 'Building and saving the Podcast.',
        );

        $service->save(
            podcast: $podcast,
        );

        $episodes = $this->components->confirm(
            question: 'Do you want to fetch the Podcast Episodes now?',
        );

        if ($episodes) {
            $this->call(
                command: ProcessEpisodes::class,
                arguments: [
                    'url' => $url,
                ],
            );
        }

        return Command::SUCCESS;
    }
}
