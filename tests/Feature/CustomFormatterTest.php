<?php

use Carbon\Carbon;
use Pelmered\LaravelUlid\Facade\Ulid;

it('formats', function (?string $prefix, ?int $randomLength) {

    /*
    Ulid::formatUlidsUsing(function (string $prefix, string $time, string $random): string {
        return  $prefix.'-'.$time.'-'.$random;
    });
    */

    /*
    $f = Ulid::getCustomFormatter();

    dd($f);
    */


    $ulid = Ulid::make(Carbon::now(), $prefix, $randomLength);

    expect($ulid)->toBeString();

    expect($ulid)->toMatchUlidFormat($prefix, $randomLength);

})->with([
    ['prefix_', 10],
    [null, 16],
    ['post_', 8],
    [null, null, null],
]);
