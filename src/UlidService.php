<?php
namespace Pelmered\LaravelUlid;

use Carbon\Carbon;
use Illuminate\Support\Str;
use lewiscowles\core\Concepts\Random\UlidRandomnessEncoder;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\Randomizer\FloatRandomGenerator;
use Pelmered\LaravelUlid\Time\StaticTimeSource;

class UlidService
{
    protected function generateUlid(Ulidable $model, ?Carbon $createdAt = null): string
    {
        /** @var Carbon $createdAt */
        $createdAt = $createdAt ?? $model->getCreatedAt() ?? now();

        return self::fromModel($model);
    }


    public static function fromModel(Ulidable $model): Ulid
    {
        return new Ulid(
            new UlidTimeEncoder(new StaticTimeSource(
                $model->getCreatedAt()->getPreciseTimestamp(3)
            )),
            new UlidRandomnessEncoder(new FloatRandomGenerator()),
            $model->getUlidPrefix(),
            $model->getUlidTimeLength(),
            $model->getUlidRandomLength(),
            $model->getUlidFormattingOptions(),
        );
    }

    /*
    public static function fromTimestamp(int $milliseconds, $prefix = '', array $options = []): Ulid
    {
        return new Ulid(
            new UlidTimeEncoder(new StaticTimeSource($milliseconds)),
            new UlidRandomnessEncoder(new FloatRandomGenerator()),
            $prefix,
            Ulid::DEFAULT_TIME_LENGTH,
            Ulid::RANDOM_LENGTH,
            $options,
        );
    }
    */

    public static function isValidUlid(string $ulid, Ulidable $model): bool
    {
        $prefix       = $model->getUlidPrefix();
        if (strlen($ulid) !== $model->getUlidLength()) {
            return false;
        }

        if (! Str::startsWith($ulid, $prefix)) {
            return false;
        }

        return ! preg_match('/[^abcdefghjkmnpqrstvwxyz0-9]/i', substr($ulid, strlen($model->getUlidPrefix())));
    }
}
