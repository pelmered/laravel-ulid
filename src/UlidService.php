<?php

namespace Pelmered\LaravelUlid;

use Carbon\Carbon;
use Illuminate\Support\Str;
use lewiscowles\core\Concepts\Random\UlidRandomnessEncoder;
use lewiscowles\core\Concepts\Time\UlidTimeEncoder;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\Formatter\UlidFormatter;
use Pelmered\LaravelUlid\Time\StaticTimeSource;
use Pelmered\LaravelUlid\ValueObject\Ulid;

class UlidService
{
    public const int DEFAULT_RANDOM_LENGTH = 16;

    public function make(?Carbon $createdAt = null, ?string $prefix = '',  ?int $randomLength = null): string
    {
        $prefix ??= '';
        $createdAt ??= Carbon::now();
        $randomLength ??= $this->getDefaultRandomLength();

        return (new UlidFactory)->generateMonotonicUlid(
            $createdAt,
            $prefix,
            $randomLength
        )->format();
    }

    public static function fromModel(Ulidable $model): Ulid
    {
        return (new UlidFactory())->generateMonotonicUlid(
            $model->getCreatedAt()->getPreciseTimestamp(3),
            $model->getUlidPrefix(),
            $model->getUlidRandomLength(),
        );
    }

    public static function isValidUlid(string $ulid, ?Ulidable $model = null, string $prefix = null): bool
    {
        if($model) {
            $prefix = $prefix ?? $model->getUlidPrefix();
            if ($prefix && ! Str::startsWith($ulid, $prefix)) {
                // dump('prefix', $ulid);
                return false;
            }

            if (strlen($ulid) !== $model->getUlidLength()) {
                // dump('length', $ulid, strlen($ulid), $model->getUlidLength());
                return false;
            }
        }

        $ulidWithoutPrefix = $prefix ? substr($ulid, strlen($prefix)) : $ulid;

        // Check for invalid characters
        // Anything except A-Z, 0-9
        if (preg_match('/[^A-Z0-9]/i', $ulidWithoutPrefix)) {
            // dump('out of range', $ulidWithoutPrefix);
            return false;
        }

        // Check for invalid characters ( I, L, O, U )
        if (preg_match('/[ILOU]/i', $ulidWithoutPrefix)) {
            // dump('invalid chars', $ulidWithoutPrefix);
            return false;
        }

        return true;
    }

    /**
     * Set a custom formatter for ULIDs
     *
     * @param callable $formatter The formatter function
     * @return void
     */
    public function formatUlidsUsing(callable $formatter): void
    {
        app(UlidFormatter::class)->formatUlidsUsing($formatter);
    }

    /**
     * Get the custom formatter if one is set
     *
     * @return \Closure|null
     */
    public function getCustomFormatter(): ?\Closure
    {
        return app(UlidFormatter::class)->getCustomFormatter();
    }

    public function getDefaultRandomLength(): int
    {
        return config('ulid.random_length', self::DEFAULT_RANDOM_LENGTH);
    }
}
