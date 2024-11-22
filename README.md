# Laravel-ULID

This package improves the ULID support in Laravel with the following features:
- Configurable prefix
- Configurable length (both time and random parts)
- Configurable case
- Configurable time source
- Configurable randomness source
- Provides a Facade for working with ULIDs

[![Latest Stable Version](https://poser.pugx.org/pelmered/laravel-ulid/v/stable)](https://packagist.org/packages/pelmered/laravel-ulid)
[![Total Downloads](https://poser.pugx.org/pelmered/laravel-ulid/d/total)](//packagist.org/packages/pelmered/laravel-ulid)
[![Monthly Downloads](https://poser.pugx.org/pelmered/laravel-ulid/d/monthly)](//packagist.org/packages/pelmered/laravel-ulid)
[![License](https://poser.pugx.org/pelmered/laravel-ulid/license)](https://packagist.org/packages/pelmered/laravel-ulid)

[![Tests](https://github.com/pelmered/laravel-ulid/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/pelmered/laravel-ulid/actions/workflows/run-tests.yml)
[![OtterWise Coverage](https://img.shields.io/endpoint?url=https://otterwise.app/badge/github/pelmered/laravel-ulid)](https://otterwise.app/github/pelmered/laravel-ulid)
[![OtterWise Type Coverage](https://img.shields.io/endpoint?url=https://otterwise.app/badge/github/pelmered/laravel-ulid/type)](https://otterwise.app/github/pelmered/laravel-ulid)

[![Tested on PHP 8.3 to 8.4](https://img.shields.io/badge/Tested%20on%20PHP-8.3%20|%208.4-brightgreen.svg?maxAge=2419200)](https://github.com/pelmered/filament-money-field/actions/workflows/tests.yml)
[![Tested on OS:es Linux, MacOS, Windows](https://img.shields.io/badge/Tested%20on%20lastest%20versions%20of-%20Ubuntu%20|%20MacOS%20|%20Windows-brightgreen.svg?maxAge=2419200)](https://github.com/pelmered/laravel-ulid/actions/workflows/tests.yml)

## TODO

- [ ] Add tests
- [ ] Add docs
- [ ] Add changelog
- [x] ~~Add license~~
- [x] ~~Add badges~~

## Installation
```bash
composer require pelmered/laravel-ulid
```
## Setup

Add the interface `Ulidable` and the trait `HasUlid` to your model.
```php

use Pelmered\LaravelUlid\LaravelUlidServiceProvider;
class User extends Authenticatable implements Ulidable
{
    use HasFactory, Notifiable, HasUlid;

    //...
}
```

## Configuration

You can configure the ULID prefix, time length and random length in the model.
```php

use Pelmered\LaravelUlid\LaravelUlidServiceProvider;
class User extends Authenticatable implements Ulidable
{
    use HasFactory, Notifiable, HasUlid;

    protected string $ulidPrefix = 'u_';

    protected int $ulidTimeLength = 10;
    protected int $ulidRandomLength = 16;

    //...
}
```
You probably shouldn't touch the time length unless you know what you are doing.\
The Random part could be optimized based on your needs. A low traffic application could probably use a shorter random part to optimize storage space and performance.

## Running tests

Run the test with `vendor/bin/pest` or `composer test`.

The test suite has some tests that require a MySQL database.
To run these tests, you need to create a `.env` file in the root of the project with the following contents,
adjusted for your setup:
```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ulid_testing
DB_TESTING_DATABASE=laravel_ulid_testing
DB_USERNAME=root
DB_PASSWORD=
```
You can also skip the MySQL tests the tests by running `vendor/bin/pest --exclude-group=mysql`.
