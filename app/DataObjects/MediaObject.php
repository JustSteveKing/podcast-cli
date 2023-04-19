<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Contracts\DataObjects\ObjectContract;

final class MediaObject implements ObjectContract
{
    public function __construct(
        public readonly string $url,
        public readonly int $length,
        public readonly string $type,
    ) {}

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'length' => $this->url,
            'type' => $this->type,
        ];
    }

    public static function fromArray(array $data): MediaObject
    {
        return new MediaObject(
            url: strval(data_get($data, 'url')),
            length: intval(data_get($data, 'length')),
            type: strval(data_get($data, 'type')),
        );
    }
}
