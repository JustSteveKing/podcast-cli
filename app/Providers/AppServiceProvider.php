<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\Services\PodcastServiceContract;
use App\Services\PodcastService;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        PodcastServiceContract::class => PodcastService::class,
    ];
}
