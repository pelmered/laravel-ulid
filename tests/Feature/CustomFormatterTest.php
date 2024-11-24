<?php

use Carbon\Carbon;
use Pelmered\LaravelUlid\Facade\Ulid;

it('formats', function (?string $prefix, ?int $timeLength, ?int $randomLength) {

    /*
    Ulid::formatUlidsUsing(function (string $prefix, string $time, string $random): string {
        return  $prefix.'-'.$time.'-'.$random;
    });
    */

    /*
    $f = Ulid::getCustomFormatter();

    dd($f);
    */

    $ulid = Ulid::make($prefix, Carbon::now(), $timeLength, $randomLength);

    expect($ulid)->toBeString();

    expect($ulid)->toMatchUlidFormat($prefix, $timeLength, $randomLength);

})->with([
    ['prefix_', 10, 10],
    [null, 10, 16],
    ['post_', 8, 8],
    [null, null, null],
]);
