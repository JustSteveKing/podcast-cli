<?php

declare(strict_types=1);

namespace App\Contracts\DataObjects;

interface ObjectContract
{
    public function toArray(): array;

    public static function fromArray(array $data): ObjectContract;
}
