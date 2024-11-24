<?php

use Pelmered\LaravelUlid\Facade\Ulid;

use function Pelmered\LaravelUlid\Tests\checkColumnSQLite;
use function Pelmered\LaravelUlid\Tests\user;

test('example', function () {
    expect(true)->toBeTrue();
});

it('creates user with ULID', function () {

    $user = user();

    expect($user->id)->toStartWith('u_')
        ->and(strlen($user->id))->toBe(28)
        ->and($user->getUlidLength())->toBe(28)
        ->and(Ulid::isValidUlid($user->id, $user))->toBeTrue();

    checkColumnSQLite($user->getTable(), $user->getKeyName());
});
