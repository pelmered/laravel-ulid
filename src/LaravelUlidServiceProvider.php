<?php
namespace Pelmered\LaravelUlid;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Pelmered\LaravelUlid\Contracts\Ulidable;

class LaravelUlidServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        $this->app->bind('ulid', function(Application $app) {
            //return new Ulid();
            return new UlidService();
        });

        //ß$this->app->bind('ulid', new UlidService() );

        /*
        $this->app->bind('ulid', UlidService::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('LaravelIntervals', JoaoBrandao\LaravelIntervals\Facades\LaravelIntervals::class);
        */

        Blueprint::macro('ulid', function (string $column = 'ulid', int $length = 26, Ulidable $model = null) {

            $length = $model ? $model->getUlidLength() : $length;

            /** @var Blueprint $this */
            return $this->char($column, $length);

            dd($this, $column, $length);


        });
        /*
    public function ulid($column = 'ulid', $length = 26)
    {
        return $this->char($column, $length);
    }
         */


        /*
        $this->publishes([
            __DIR__.'/../config/courier.php' => config_path('courier.php'),
        ]);
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'courier');
        */
    }
    public function register(): void
    {
        /*
        $this->mergeConfigFrom(
            __DIR__.'/../config/courier.php', 'courier'
        );
        */
    }
}
