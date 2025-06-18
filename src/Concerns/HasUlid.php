<?php

namespace Pelmered\LaravelUlid\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Pelmered\LaravelUlid\Contracts\Ulidable;
use Pelmered\LaravelUlid\UlidService;
use Pelmered\LaravelUlid\ValueObject\Ulid;

/**
 * @phpstan-require-extends Model
 *
 * @phpstan-require-implements Ulidable
 */
trait HasUlid
{
    use HasUniqueStringIds;

    public function newUniqueId(): string
    {
        return (string) UlidService::fromModel($this);
    }

    protected function isValidUniqueId($ulid): bool
    {
        return UlidService::isValidUlid($ulid, $this);
    }

    public function getUlidPrefix(): string
    {
        if (property_exists($this, 'ulidPrefix')) {
            return Str::substr($this->ulidPrefix, 0, 8);
        }

        if (method_exists($this, 'ulidPrefix')) {
            return Str::substr($this->ulidPrefix(), 0, 8);
        }

        return '';
    }

    public static function getTableName(): string
    {
        return (new static)->getTable();
    }

    public function getUlidRandomLength(): int
    {
        if (property_exists($this, 'ulidRandomLength')) {
            return (int) $this->ulidRandomLength;
        }

        return config('ulid.random_length', UlidService::DEFAULT_RANDOM_LENGTH);
    }

    public function getUlidLength(): int
    {
        return strlen($this->getUlidPrefix()) + Ulid::TIME_LENGTH + $this->getUlidRandomLength();
    }

    public function getUlidFormattingOptions(): array
    {
        return [];
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at ?? Carbon::now();
    }
}
