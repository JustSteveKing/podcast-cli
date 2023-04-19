<?php

declare(strict_types=1);

namespace App\Commands\Podcasts;

use App\Models\Episode;
use App\Models\Podcast;
use App\Repositories\PodcastRepository;
use Illuminate\Support\Facades\Process;
use LaravelZero\Framework\Commands\Command;

final class ListenToEpisode extends Command
{
    protected $signature = 'listen';

    protected $description = 'Listen to an episode of a Podcast.';

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

        $episode = $this->components->choice(
            question: 'Which Episode would you like to listen to?',
            choices: $repository->episodes(
                podcast: $podcast->getKey(),
            )->map(fn (Episode $episode): string => $episode->title)->toArray(),
        );

        $episode = $repository->fromEpisodeTitle(
            title: $episode,
        );

        $file = $episode->media['url'];

        $process = Process::command(
            command: "mpg123 $file",
        );

        $process = $process->start();

        $this->components->info(
            string: 'Press "S" to stop.',
        );

        while ($process->running()) {
            if (readline('Listening for the stop signal ....')) {

                $process->signal(SIGTERM);
                break;
            }

            usleep(100000);
        }

        $repository->markListenedTo(
            episode: $episode,
        );

        return Command::SUCCESS;
    }
}
