<?php

namespace Pelmered\LaravelUlid\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Illuminate\Contracts\Config\Repository;
use PhpStaticAnalysis\Attributes\Param;
use PhpStaticAnalysis\Attributes\Returns;
use PhpStaticAnalysis\Attributes\Type;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

#[WithMigration]
class TestbenchTestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;
    //use DatabaseTransactions;
    use RefreshDatabase;

    protected function usesMySqlConnection($app): void
    {
        tap($app['config'], static function (Repository $config) {
            $config->set('database.default', 'mysql');
            $config->set('database.connections.mysql', [
                'driver'   => 'mysql',
                'host'     => config('database.connections.mysql.host', '127.0.0.1'),
                'port'     => config('database.connections.mysql.port', '3306'),
                'database' => config('database.connections.mysql.database', 'laravel_ulid_testing'),
                'username' => config('database.connections.mysql.username', 'root'),
                'password' => config('database.connections.mysql.password', null),
                'prefix'   => '',
            ]);

        });
        //$app['config']->set('database.default', 'mysql');
    }

    protected function usesSqliteConnection($app): void
    {
        $app['config']->set('database.default', 'sqlite');
    }

    #[Param(app: Application::class)] // the second array
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
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
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
