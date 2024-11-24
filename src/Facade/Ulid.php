<?php

namespace Pelmered\LaravelUlid\Facade;

use Illuminate\Support\Facades\Facade;

class Ulid extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ulid';
    }
}
