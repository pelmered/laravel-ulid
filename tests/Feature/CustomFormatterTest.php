<?php


use Illuminate\Database\Schema\Blueprint;
use Pelmered\LaravelUlid\Facade\Ulid;

it('formats' , function () {


    /*
    Ulid::formatUlidsUsing(function (string $prefix, string $time, string $random): string {
        return  $prefix.'-'.$time.'-'.$random;
    });
    */

    /*
    $f = Ulid::getCustomFormatter();

    dd($f);
    */

    $ulid = Ulid::make(null, 'prefix_');
    expect($ulid)->toBeString();

    dump($ulid);


    //expect($ulid)->toMatch('/^[a-z0-9]{26}-[0-9]{10}-[a-z0-9]{16}$/');


});
