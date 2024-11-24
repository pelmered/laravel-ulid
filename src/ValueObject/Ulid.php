<?php

namespace Pelmered\LaravelUlid\ValueObject;

use lewiscowles\core\Concepts\Random\RandomnessEncoderInterface;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use Pelmered\LaravelUlid\Formatter\UlidFormatter;
use Stringable;

class Ulid implements Stringable
{
    public function __construct(
        protected(set) UlidTimeEncoder $timeEncoder,
        protected(set) RandomnessEncoderInterface $randomEncoder,
        protected(set) string $prefix = '',
        protected int $timeLength = 10,
        protected int $randomLength = 16,
    ) {}

    public function format(): string
    {
        return app(UlidFormatter::class)->format($this);
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
