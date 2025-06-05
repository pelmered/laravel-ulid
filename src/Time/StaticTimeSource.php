<?php

namespace Pelmered\LaravelUlid\Time;

use lewiscowles\core\Concepts\Time\TimeSourceInterface;

readonly class StaticTimeSource implements TimeSourceInterface
{
    public function __construct(private \DateTimeInterface|int $timestamp) {}

    public function getTime(): int
    {
        if (is_int($this->timestamp)) {
            return $this->timestamp;
        }

        return $this->timestamp->getMicrosecond();
    }
}
