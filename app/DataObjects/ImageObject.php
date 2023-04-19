<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Contracts\DataObjects\ObjectContract;

final class ImageObject implements ObjectContract
{
    public function __construct(
        public readonly string $uri,
        public readonly string $link,
        public readonly string $title,
    ) {}

    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'link' => $this->link,
            'title' => $this->title,
        ];
    }

    public static function fromArray(array $data): ImageObject
    {
        return new ImageObject(
            uri: strval(data_get($data, 'uri')),
            link: strval(data_get($data, 'link')),
            title: strval(data_get($data, 'title')),
        );
    }
}
