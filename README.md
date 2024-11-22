# Laravel-ULID

This package improves the ULID support in Laravel with the following features:
- Configurable prefix
- Configurable length (both time and random parts)
- Configurable case
- Configurable time source
- Configurable randomness source
- Provides a Facade for working with ULIDs

## TODO

- [ ] Add tests
- [ ] Add docs
- [ ] Add changelog
- [ ] Add license
- [ ] Add badges




## Running tests

Run the test with `vendor/bin/pest` or `composer test`.

The test suite has some tests that require a MySQL database.
To run these tests, you need to create a `.env` file in the root of the project with the following contents,
adjusted for your setup:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ulid_testing
DB_TESTING_DATABASE=laravel_ulid_testing
DB_USERNAME=root
DB_PASSWORD=
```
You can also skip the MySQL tests the tests by running `vendor/bin/pest --exclude-group=mysql`.
