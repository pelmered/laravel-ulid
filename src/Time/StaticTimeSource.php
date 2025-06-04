<?php

namespace Pelmered\LaravelUlid\Time;

use Carbon\Carbon;
use lewiscowles\core\Concepts\Time\TimeSourceInterface;

readonly class StaticTimeSource implements TimeSourceInterface
{
    public function __construct(private \DateTimeInterface|int $timestamp) {}

    public function getTime(): int
    {
        if (is_int($this->timestamp)) {
            return $this->timestamp;
        }

        return (int) Carbon::instance($this->timestamp)->getPreciseTimestamp(3);
        // This needs PHP 8.4
        // return $this->timestamp->getMicrosecond();
    }
}
