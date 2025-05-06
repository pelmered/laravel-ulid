<?php

namespace Pelmered\LaravelUlid\ValueObject;

use Carbon\Carbon;
use DateTimeInterface;
use Pelmered\LaravelUlid\Formatter\UlidFormatter;
use Pelmered\LaravelUlid\UlidFactory;
use Stringable;

class Ulid implements Stringable
{
    const int TIME_LENGTH = 10;

    public function __construct(
        protected string $prefix = '',
        protected string $timePart,
        protected string $randomPart,
    ) {}

    public static function make(
        Carbon|DateTimeInterface|int|null $time = null,
        string $prefix = '',
        int $randomLength = 16,
    ): self {
        return (new UlidFactory)->generateMonotonicUlid($time, $prefix, $randomLength);
    }

    public function format(): string
    {
        return app(UlidFormatter::class)->format(
            $this->prefix,
            $this->timePart,
            $this->randomPart,
        );
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
