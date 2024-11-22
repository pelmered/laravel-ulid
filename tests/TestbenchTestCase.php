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

#[WithMigration]
class TestbenchTestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench;
    //use DatabaseTransactions;
    use RefreshDatabase;


    #[Param(app: Application::class)] // the second array
    #[Returns('void')]
    protected function defineEnvironment($app)
    {
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
