<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Contracts\DataObjects\ObjectContract;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

final class EpisodeObject implements ObjectContract
{
    public function __construct(
        public readonly string $title,
        public readonly string $link,
        public readonly string $description,
        public readonly MediaObject $media,
        public readonly CarbonInterface $publishedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'link' => $this->link,
            'description' => $this->description,
            'media' => $this->media,
            'published_at' => $this->publishedAt,
        ];
    }

    public static function fromArray(array $data): EpisodeObject
    {
        return new EpisodeObject(
            title: strval(data_get($data, 'title')),
            link: strval(data_get($data, 'link')),
            description: strval(data_get($data, 'description')),
            media: MediaObject::fromArray(
                data: (array) data_get($data, 'media'),
            ),
            publishedAt: Carbon::parse(
                time: strval(data_get($data, 'published_at')),
            ),
        );
    }
}
