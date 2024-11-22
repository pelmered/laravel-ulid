<?php

use Pelmered\LaravelUlid\Facade\Ulid;
use function Pelmered\LaravelUlid\Tests\user;

it('creates ULID from model using facade',  function () {
    $user = user();

    Ulid::fromModel($user);

    expect($user->id)->toStartWith('u_')
        ->and(strlen($user->id))->toBe(28)
        ->and($user->getUlidLength())->toBe(28)
        ->and(Ulid::isValidUlid($user->id, $user))->toBeTrue();
});
