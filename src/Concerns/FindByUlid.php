<?php
namespace Pelmered\LaravelUlid\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Pelmered\LaravelUlid\Contracts\Ulidable;

/**
 * @phpstan-require-extends Model
 * @phpstan-require-implements Ulidable
 *
 * @method static findMany(array|Arrayable $value, array|string|string[] $columns)
 * @method refresh()
 * @method static where($idColumn, string $string, string $id)
 * @method static idColumn()
 * @method static getTableName()
 */
trait FindByUlid
{
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
}
