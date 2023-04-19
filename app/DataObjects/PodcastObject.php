<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Contracts\DataObjects\ObjectContract;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

final class PodcastObject implements ObjectContract
{
    public function __construct(
        public readonly string $title,
        public readonly string $generator,
        public readonly string $copyright,
        public readonly string $language,
        public readonly string $link,
        public readonly string $feed,
        public readonly string $description,
        public readonly ImageObject $image,
        public readonly CarbonInterface $publishedAt,
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'generator' => $this->generator,
            'copyright' => $this->copyright,
            'language' => $this->language,
            'link' => $this->link,
            'feed_url' => $this->feed,
            'description' => $this->description,
            'image' => $this->image->toArray(),
            'published_at' => $this->publishedAt,
        ];
    }

    public static function fromArray(array $data): PodcastObject
    {
        return new PodcastObject(
            title: strval(data_get($data, 'title')),
            generator: strval(data_get($data, 'generator')),
            copyright: strval(data_get($data, 'copyright')),
            language: strval(data_get($data, 'language')),
            link: strval(data_get($data, 'link')),
            feed: strval(data_get($data, 'feed_url')),
            description: strval(data_get($data, 'description')),
            image: ImageObject::fromArray(
                data: (array) data_get($data, 'image'),
            ),
            publishedAt: Carbon::parse(
                time: strval(data_get($data, 'published_at')),
            ),
        );
    }
}
