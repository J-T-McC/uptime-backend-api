<?php

namespace App\Services\HashId;

use Sqids\Sqids;

readonly class HashId
{
    private Sqids $squid;

    public function __construct()
    {
        $this->squid = new Sqids(
            config('hash-id.alphabet'),
            config('hash-id.min_length'),
        );
    }

    public function encode(int $id): string
    {
        return $this->squid->encode([$id]);
    }

    public function decode(string $hashId): ?int
    {
        if (is_numeric($hashId)) {
            return (int) $hashId;
        }

        return $this->squid->decode($hashId)[0] ?? null;
    }
}
