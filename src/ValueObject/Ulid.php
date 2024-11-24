<?php
namespace Pelmered\LaravelUlid\ValueObject;

use Illuminate\Database\Eloquent\Model;
use lewiscowles\core\Concepts\Random\UlidRandomnessEncoder;
use lewiscowles\core\ValueTypes\PositiveNumber;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use lewiscowles\core\Concepts\Random\RandomnessEncoderInterface;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\Formatter\UlidFormatter;
use Pelmered\LaravelUlid\Randomizer\FloatRandomGenerator;
use Pelmered\LaravelUlid\Time\StaticTimeSource;


class Ulid implements \Stringable
{
    public function __construct(
        protected(set) UlidTimeEncoder $timeEncoder,
        protected(set) RandomnessEncoderInterface $randomEncoder,
        protected(set) string $prefix = '',
        protected int $timeLength = 10,
        protected int $randomLength = 16,
    ) {
    }


    public function format(): string
    {
        return app(UlidFormatter::class)->format($this);

        /*
        return app('ulid')->format(
            $this->prefix,
            $this->timeEncoder->encode(new PositiveNumber($this->timeLength)),
            $this->randomEncoder->encode(new PositiveNumber($this->randomLength))
        );
        */
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
