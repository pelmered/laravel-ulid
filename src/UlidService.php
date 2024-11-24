<?php

namespace Pelmered\LaravelUlid;

use Carbon\Carbon;
use Illuminate\Support\Str;
use lewiscowles\core\Concepts\Random\UlidRandomnessEncoder;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\Randomizer\FloatRandomGenerator;
use Pelmered\LaravelUlid\Time\StaticTimeSource;
use Pelmered\LaravelUlid\ValueObject\Ulid;

class UlidService
{
    public const int DEFAULT_TIME_LENGTH = 10;

    public const int DEFAULT_RANDOM_LENGTH = 16;

    public function make(?string $prefix = '', ?Carbon $createdAt = null, ?int $timeLength = null, ?int $randomLength = null): string
    {
        $prefix ??= '';
        $createdAt ??= Carbon::now();
        $timeLength ??= $this->getDefaultTimeLength();
        $randomLength ??= $this->getDefaultRandomLength();

        return (new Ulid(
            new UlidTimeEncoder(new StaticTimeSource(
                $createdAt->getPreciseTimestamp(3)
            )),
            new UlidRandomnessEncoder(new FloatRandomGenerator),
            $prefix,
            $timeLength,
            $randomLength,
        ))->format();
    }

    public static function fromModel(Ulidable $model): Ulid
    {
        return new Ulid(
            new UlidTimeEncoder(new StaticTimeSource(
                $model->getCreatedAt()->getPreciseTimestamp(3)
            )),
            new UlidRandomnessEncoder(new FloatRandomGenerator),
            $model->getUlidPrefix(),
            $model->getUlidTimeLength(),
            $model->getUlidRandomLength(),
        );
    }

    public static function isValidUlid(string $ulid, ?Ulidable $model = null): bool
    {
        //TODO: Handle $model = null case
        $prefix = $model->getUlidPrefix();
        if (strlen($ulid) !== $model->getUlidLength()) {
            return false;
        }

        if (! Str::startsWith($ulid, $prefix)) {
            return false;
        }

        return ! preg_match('/[^a-z0-9]/i', substr($ulid, strlen($model->getUlidPrefix())));
    }

    public function getDefaultTimeLength(): int
    {
        return config('ulid.time_length', self::DEFAULT_TIME_LENGTH);
    }

    public function getDefaultRandomLength(): int
    {
        return config('ulid.random_length', self::DEFAULT_RANDOM_LENGTH);
    }
}
