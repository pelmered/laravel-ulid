<?php

use Illuminate\Support\Facades\Schema;
use Pelmered\LaravelUlid\Facade\Ulid;

use function Pelmered\LaravelUlid\Tests\checkColumnSQLite;
use function Pelmered\LaravelUlid\Tests\user;

beforeEach(function () {
    // Ensure users table exists in the test database
    if (! Schema::hasTable('users')) {
        Schema::create('users', function ($table) {
            $table->ulid('id', 28)->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
});

it('creates user with ULID', function () {
    $user = user();

    expect($user->id)->toStartWith('u_')
        ->and(strlen($user->id))->toBe(28)
        ->and($user->getUlidLength())->toBe(28)
        ->and(Ulid::isValidUlid($user->id, $user))->toBeTrue();

    checkColumnSQLite($user->getTable(), $user->getKeyName());
});
