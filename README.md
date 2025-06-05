# Laravel-ULID

This package improves the ULID support in Laravel with the following features:
- Configurable prefix
- Configurable length (both time and random parts)
- Configurable formatting/case
- Configurable time source
- Configurable randomness source
- Provides a Facade for working with ULIDs

## ULIDs - What and why

ULIDs are a compact, time-ordered, globally unique identifier that are used to
identify resources in a way that is both human-readable and machine-readable.

For a more in-depth technical explanation, see the [ULID specification](https://github.com/ulid/spec)

One of my personal favorite things about ULIDs is that you can double-click on them to select and copy. 
Try to select and copy these IDs: `u_01ARZ3NDEKTSV4RRFFQ69G5FAVY` and `4f839b45-fcca-495b-b9ed-0e365270b1c3`. 
The prefixes also makes it immediately clear what kind of ID it is when you see it.

This is great when you are passing IDs around to colleagues or coworkers when debugging or working with support.
The hyphens also look pretty ugly and do not have any real meaning for a human.

<hr>

[![Latest Stable Version](https://poser.pugx.org/pelmered/laravel-ulid/v/stable)](https://packagist.org/packages/pelmered/laravel-ulid)
[![Total Downloads](https://poser.pugx.org/pelmered/laravel-ulid/d/total)](//packagist.org/packages/pelmered/laravel-ulid)
[![Monthly Downloads](https://poser.pugx.org/pelmered/laravel-ulid/d/monthly)](//packagist.org/packages/pelmered/laravel-ulid)
[![License](https://poser.pugx.org/pelmered/laravel-ulid/license)](https://packagist.org/packages/pelmered/laravel-ulid)

[![Tests](https://github.com/pelmered/laravel-ulid/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/pelmered/laravel-ulid/actions/workflows/run-tests.yml)
[![OtterWise Coverage](https://img.shields.io/endpoint?url=https://otterwise.app/badge/github/pelmered/laravel-ulid)](https://otterwise.app/github/pelmered/laravel-ulid)
[![OtterWise Type Coverage](https://img.shields.io/endpoint?url=https://otterwise.app/badge/github/pelmered/laravel-ulid/type)](https://otterwise.app/github/pelmered/laravel-ulid)

[![Tested on PHP 8.4](https://img.shields.io/badge/Tested%20on%20PHP-%208.4-brightgreen.svg?maxAge=2419200)](https://github.com/pelmered/filament-money-field/actions/workflows/tests.yml)
[![Tested on OS:es Linux, MacOS, Windows](https://img.shields.io/badge/Tested%20on%20lastest%20versions%20of-%20Ubuntu%20|%20MacOS%20|%20Windows-brightgreen.svg?maxAge=2419200)](https://github.com/pelmered/laravel-ulid/actions/workflows/tests.yml)

## TODO

- [ ] Add more tests
- [ ] Add docs
- [x] ~~Add license~~
- [x] ~~Add badges~~
- [ ] Implement custom formatter support

## Roadmap
- [ ] Migration tool for migrating an existing database with numeric or UUIDs to ULIDs. Probably as a separate suggested package.

## Installation
```bash
composer require pelmered/laravel-ulid
```
## Setup

Add the interface `Ulidable` and the trait `HasUlid` to your model.
```php

use Pelmered\LaravelUlid\LaravelUlidServiceProvider;
class Post extends Model implements Ulidable
{
    use HasUlid;

    //...
}
```

## Configuration

You can configure the ULID prefix, time length and random length in the model.

```php

use Pelmered\LaravelUlid\LaravelUlidServiceProvider;use Pelmered\LaravelUlid\ValueObject\Ulid;
class Post extends Model implements Ulidable
{
    use HasUlid;

    protected string $ulidPrefix = 'p_';
    protected int $ulidTimeLength = 10;
    protected int $ulidRandomLength = 16;
    protected string $ulidOptions = Ulid::OPTION_UPPERCASE;

    //...
}
```
You probably shouldn't touch the time length unless you know what you are doing.\
The Random part could be optimized based on your needs. A low traffic application could probably use a shorter random part to optimize storage space and performance.

## Usage (Work in progress)


### Migrations

To create a ULID column in a migration, you can use the `ulid` or `modelULid` methods like this to get the correct length.
```php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a table with an ULID primary key like this:
        Schema::create('users', function (Blueprint $table) {
            $table->uLid('id', (new \Workbench\App\Models\User())->getUlidLength())->primary();
        });
        // or like this:
        Schema::create('posts', function (Blueprint $table) {
            $table->modelULid('id', User::class)->primary();
        });
    }
}
```



### Facade

```php
ULID::make(); // '1234567890123456789' - Normal ULID
ULID::make('p_', Carbon::parse('2010-11-12 01:02:03')); // 'u_1234567890123456789' - ULID with prefix 
ULID::make('p_', 10, 10 ); // 'u_1234567890123456789' - ULID with prefix
ULID::fromModel($model);
ULID::isValidUlid('u_1234567890123456789', $model);
```
### Formatting
```dotenv

```

### Config

Publish the config file `config/ulid.php` to your project.
```bash
php artisan vendor:publish --tag=ulid-config
```

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
