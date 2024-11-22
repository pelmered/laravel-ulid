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
            return new UlidService();
        });

        Blueprint::macro('modelUlid', function (string $column = 'ulid', Ulidable|string $model = null) {

            if (is_string($model)) {
                $model = new $model;
            }

            $length = $model ? $model->getUlidLength() : 26;

            /** @var Blueprint $this */
            return $this->char($column, $length);
        });

        $this->publishes([
            __DIR__.'/../config/ulid.php' => config_path('ulid.php'),
        ]);
    }
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ulid.php', 'ulid'
        );
    }
}
