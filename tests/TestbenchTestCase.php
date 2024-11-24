<?php

namespace Pelmered\LaravelUlid\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;
use Pelmered\LaravelUlid\LaravelUlidServiceProvider;
use PhpStaticAnalysis\Attributes\Param;
use PhpStaticAnalysis\Attributes\Returns;
use Workbench\App\Providers\CustomFormatterServiceProvider;

#[WithMigration]
class TestbenchTestCase extends TestCase
{
    use RefreshDatabase;
    use WithWorkbench;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelUlidServiceProvider::class,
            CustomFormatterServiceProvider::class,
        ];
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
    protected function defineEnvironment($app)
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
            ]);

            // Setup queue database connections.
            /*
            $config([
                'queue.batching.database' => 'testbench',
                'queue.failed.database' => 'testbench',
            ]);
            */
        });
    }
}
