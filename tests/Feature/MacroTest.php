<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\User;

use function Pelmered\LaravelUlid\Tests\checkColumnMySQL;
use function Pelmered\LaravelUlid\Tests\checkColumnSQLite;

it('macro exists on blueprint', function () {
    expect(Blueprint::hasMacro('modelUlid'))->toBeTrue();
});

it('macro returns correct ulid column on SQLite', function () {
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

it('macro returns correct ulid column on MySQL', function () {

    $this->usesMySqlConnection(app());
    Schema::dropIfExists('test');

    Schema::create('test', function (Blueprint $table) {
        $col = $table->modelUlid('id', User::class)->primary();

        expect($col)->toBeInstanceOf(Illuminate\Database\Schema\ColumnDefinition::class);
        expect($col->length)->toBe(28);
        expect($col->type)->toBe('char');
        expect($col->name)->toBe('id');
        expect($col->primary)->toBeTrue();
    });

    checkColumnMySQL('test', 'id', (new User)->getUlidLength());
})->group('mysql');
