<?php

namespace Pelmered\LaravelUlid\Tests;

use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\PHPUnitTestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestbenchTestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toMatchUlidFormat', function (?string $prefix, ?int $randomLength = 16) {
    $prefix ??= '';
    $timeLength = 10;
    $randomLength ??= 16;

    return $this->toMatch('/^'.$prefix.'[A-Z0-9]{'.$timeLength.'}[A-Z0-9]{'.$randomLength.'}$/');
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function user(): User
{
    return User::factory()->create();
}

function post(): User
{
    return Post::factory()->create();
}

function checkColumnSQLite(string $tableName, string $columnName): void
{
    expect(Schema::hasColumn($tableName, $columnName))->toBeTrue();
    expect(Schema::getColumnType($tableName, $columnName, true))->toBeIn(['varchar', 'text']);

    $index = Schema::getIndexes($tableName)[1];
    expect($index['columns'][0])->toBe($columnName);
    expect($index['unique'])->toBeTrue();
    expect($index['primary'])->toBeTrue();
}

function checkColumnMySQL(string $tableName, string $columnName, $length = 28): void
{
    expect(Schema::hasColumn($tableName, $columnName))->toBeTrue();
    expect(Schema::getColumnType($tableName, $columnName, true))->toBe('char(28)');

    $column = Schema::getColumns($tableName)[0];

    expect($column['name'])->toBe($columnName);
    expect($column['type'])->toBe('char('.$length.')');
    expect($column['nullable'])->toBeFalse();
    expect($column['name'])->toBe($columnName);
    expect($column['default'])->toBeNull();
    expect($column['auto_increment'])->toBeFalse();

    $index = Schema::getIndexes($tableName)[0];

    expect($index['name'])->toBe('primary');
    expect($index['columns'][0])->toBe($columnName);
    expect($index['type'])->toBe('btree');
    expect($index['unique'])->toBeTrue();
    expect($index['primary'])->toBeTrue();
}
