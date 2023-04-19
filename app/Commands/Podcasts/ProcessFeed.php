<?php

declare(strict_types=1);

namespace App\Commands\Podcasts;

use LaravelZero\Framework\Commands\Command;

final class ProcessFeed extends Command
{
    protected $signature = 'import';

    protected $description = 'Import a Podcast feed.';

    public function handle(): int
    {
        // get feed url
        $url = base_path('tests/Fixtures/laravel-news.xml');

        // get feed content

        // process
        $xml = simplexml_load_string(file_get_contents($url));

        dd($xml->attributes('channel'));


        return Command::SUCCESS;
    }
}
