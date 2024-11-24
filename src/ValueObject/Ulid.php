<?php

namespace Pelmered\LaravelUlid\ValueObject;

use lewiscowles\core\Concepts\Random\RandomnessEncoderInterface;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use lewiscowles\core\ValueTypes\PositiveNumber;
use Pelmered\LaravelUlid\Formatter\UlidFormatter;
use Stringable;

class Ulid implements Stringable
{
    public function __construct(
        protected UlidTimeEncoder $timeEncoder,
        protected RandomnessEncoderInterface $randomEncoder,
        protected string $prefix = '',
        protected int $timeLength = 10,
        protected int $randomLength = 16,
    ) {}

    public function format(): string
    {
        return app(UlidFormatter::class)->format(
            $this->prefix,
            $this->timeEncoder->encode(new PositiveNumber($this->timeLength)),
            $this->randomEncoder->encode(new PositiveNumber($this->randomLength))
        );
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
