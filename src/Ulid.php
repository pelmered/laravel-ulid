<?php
namespace Pelmered\LaravelUlid;

use Illuminate\Database\Eloquent\Model;
use lewiscowles\core\Concepts\Random\UlidRandomnessEncoder;
use lewiscowles\core\ValueTypes\PositiveNumber;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use lewiscowles\core\Concepts\Random\RandomnessEncoderInterface;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\Randomizer\FloatRandomGenerator;
use Pelmered\LaravelUlid\Time\StaticTimeSource;

class Ulid
{
    public const DEFAULT_TIME_LENGTH = 10;

    public const DEFAULT_RANDOM_LENGTH = 16;
    public const RANDOM_LENGTH = 16;

    public const OPTION_LOWERCASE = 'lowercase';
    public const OPTION_UPPERCASE = 'uppercase';

    public function __construct(
        protected UlidTimeEncoder $timeEncoder,
        protected RandomnessEncoderInterface $randomEncoder,
        protected string $prefix = '',
        protected int $timeLength = 10,
        protected int $randomLength = 16,
        protected array $options = [],
    ) {
    }


    public function generate(): string
    {
        return $this->format(sprintf(
            '%s%s%s',
            $this->prefix,
            $this->timeEncoder->encode(new PositiveNumber($this->timeLength)),
            $this->randomEncoder->encode(new PositiveNumber($this->randomLength))
        ));
    }

    protected function format(string $ulid): string
    {
        foreach($this->options as $option) {
            $ulid = match($option) {
                self::OPTION_UPPERCASE => strtoupper($ulid),
                self::OPTION_LOWERCASE => strtolower($ulid),
                default => $ulid
            };
        }

        return $ulid;
    }

    public function __toString(): string
    {
        return $this->generate();
    }
}
