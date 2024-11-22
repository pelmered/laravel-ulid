<?php
namespace Pelmered\LaravelUlid\Concerns;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Concerns\HasUniqueStringIds;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
//use Pelmered\LaravelUlid\Facade\Ulid;
use Pelmered\LaravelUlid\UlidService;
use Pelmered\LaravelUlid\Ulid;

trait HasUlid
{
    use HasUniqueStringIds;

    public function newUniqueId(): string
    {
        return UlidService::fromModel($this);
    }

    protected function isValidUniqueId($ulid): bool
    {
        return UlidService::isValidUlid($ulid, $this);
    }

    public static function find(string|Arrayable|array $value, array|string $columns = ['*']): ?self
    {
        if ($value instanceof static) {
            return $value->refresh();
        }

        if (is_array($value) || $value instanceof Arrayable) {
            return static::findMany($value, $columns);
        }

        return static::findByUlid($value, $columns);
    }

    public static function findById(string $id, array|string $columns = ['*'], bool $withTrashed = false): ?self
    {
        return static::findByUlid($id, $columns, $withTrashed);
    }
    public static function findByUlid(string $id, array|string $columns = ['*'], bool $withTrashed = false): ?self
    {
        return Cache::remember(
            implode('_', [
                self::getTableName(),
                $id,
                ...$columns,
                $withTrashed ? 'withTrashed' : '',
            ]),
            60,
            static function () use ($withTrashed, $id, $columns) {
                return self::where(static::idColumn(), '=', $id)
                           ->when($withTrashed, function ($query) {
                               return $query->withTrashed();
                           })
                           ->first($columns);
            }
        );
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
        return (new static())->getTable();
    }

    /**
     * The name of the column that should be used for the UUID.
     *
     * @return string
     */
    public static function idColumn(): string
    {
        return (new static())->primaryKey ?? 'id';
    }

    public function getKeyName(): string
    {
        return static::idColumn();
    }

    public function getUlidTimeLength(): int
    {
        if (property_exists($this, 'ulidTimeLength')) {
            return (int) $this->ulidTimeLength;
        }

        return Ulid::DEFAULT_TIME_LENGTH;
    }

    public function getUlidRandomLength(): int
    {
        if (property_exists($this, 'ulidRandomLength')) {
            return (int) $this->ulidRandomLength;
        }

        return Ulid::DEFAULT_RANDOM_LENGTH;
    }

    public function getUlidLength(): int
    {
        return (strlen($this->getUlidPrefix()) + $this->getUlidTimeLength() + $this->getUlidRandomLength());
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
