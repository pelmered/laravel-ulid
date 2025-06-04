<?php

use Carbon\Carbon;
use Pelmered\LaravelUlid\ValueObject\Ulid;

it('creates ulid value object with default parameters', function () {
    $timestamp = Carbon::now()->getPreciseTimestamp(3);
    $ulid = Ulid::make($timestamp);

    expect($ulid)
        ->toBeInstanceOf(Ulid::class)
        ->and($ulid->format())
        ->toBeString()
        ->toHaveLength(26);
});

it('creates ulid value object with custom prefix', function () {
    $timestamp = Carbon::now()->getPreciseTimestamp(3);
    $prefix = 'test_';
    $ulid = Ulid::make($timestamp, $prefix);

    expect($ulid->format())
        ->toBeString()
        ->toStartWith($prefix)
        ->toHaveLength(26 + strlen($prefix));
});

it('creates ulid value object with custom lengths', function () {
    $timestamp = Carbon::now()->getPreciseTimestamp(3);
    $randomLength = 12;

    $ulid = Ulid::make($timestamp, '', $randomLength);

    expect($ulid->format())
        ->toBeString()
        ->toHaveLength(Ulid::TIME_LENGTH + $randomLength);
});

it('generates different random parts for same timestamp', function () {
    $timestamp = Carbon::now()->getPreciseTimestamp(3);

    $ulid1 = Ulid::make($timestamp)->format();
    $ulid2 = Ulid::make($timestamp)->format();

    expect($ulid1)->not->toBe($ulid2);
});
