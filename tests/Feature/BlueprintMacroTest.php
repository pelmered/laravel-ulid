<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

use function Pelmered\LaravelUlid\Tests\checkColumnMySQL;
use function Pelmered\LaravelUlid\Tests\checkColumnSQLite;

it('macro exists on blueprint', function () {
    expect(Blueprint::hasMacro('modelUlid'))->toBeTrue();
});

it(' returns correct ulid column on SQLite', function () {
    // Drop the table first if it exists (to avoid errors)
    Schema::dropIfExists('test');

    Schema::create('test', function (Blueprint $table) {
        $col = $table->modelUlid('id', User::class)->primary();

        expect($col)->toBeInstanceOf(Illuminate\Database\Schema\ColumnDefinition::class)
            ->and($col->length)->toBe(28)
            ->and($col->type)->toBe('char')
            ->and($col->name)->toBe('id')
            ->and($col->primary)->toBeTrue();
    });

    checkColumnSQLite('test', 'id');
});

it('returns correct ulid column on MySQL', function () {

    $this->usesMySqlConnection(app());

    // Skip if MySQL connection isn't available
    try {
        $connection = app('db')->connection('mysql');
        $connection->getPdo();
    } catch (\Exception $e) {
        $this->markTestSkipped('MySQL connection is not available. Set up MySQL in the .env file to run this test.');
        return;
    }

    Schema::dropIfExists('test');

    Schema::create('test', static function (Blueprint $table) {
        $col = $table->modelUlid('id', User::class)->primary();

        expect($col)->toBeInstanceOf(Illuminate\Database\Schema\ColumnDefinition::class);
        expect($col->length)->toBe(28);
        expect($col->type)->toBe('char');
        expect($col->name)->toBe('id');
        expect($col->primary)->toBeTrue();
    });

    checkColumnMySQL('test', 'id', (new User)->getUlidLength());
})->group('mysql');
