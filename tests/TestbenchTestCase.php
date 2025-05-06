<?php

namespace Pelmered\LaravelUlid\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase;
use Pelmered\LaravelUlid\LaravelUlidServiceProvider;
use PhpStaticAnalysis\Attributes\Param;
use PhpStaticAnalysis\Attributes\Returns;
use Workbench\App\Providers\CustomFormatterServiceProvider;
use function Orchestra\Testbench\workbench_path;

#[WithMigration]
class TestbenchTestCase extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelUlidServiceProvider::class,
            CustomFormatterServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }

    protected function usesMySqlConnection($app): void
    {
        tap($app['config'], static function (Repository $config) {
            $config->set('database.default', 'mysql');
            $config->set('database.connections.mysql', [
                'driver' => 'mysql',
                'host' => config('database.connections.mysql.host', '127.0.0.1'),
                'port' => config('database.connections.mysql.port', '3306'),
                'database' => config('database.connections.mysql.database', 'laravel_ulid_testing'),
                'username' => config('database.connections.mysql.username', 'root'),
                'password' => config('database.connections.mysql.password', null),
                'prefix' => '',
            ]);
        });
    }

    protected function usesSqliteConnection($app): void
    {
        $app['config']->set('database.default', 'sqlite');
    }

    #[Param(app: Application::class)]
    #[Returns('void')]
    protected function defineEnvironment($app): void
    {
        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);

        // Setup default database to use sqlite :memory:
        tap($app['config'], static function (Repository $config) {
            $config->set('database.default', 'testbench');
            $config->set('database.connections.testbench', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
                'foreign_key_constraints' => true,
            ]);
        });
    }

    /**
     * Set up the custom ULID formatter
     */
    protected function setupFormatter(): void
    {
        app('ulid')->formatUlidsUsing(function (string $prefix, string $time, string $random): string {
            return $prefix . $time . $random;
        });
    }
}
