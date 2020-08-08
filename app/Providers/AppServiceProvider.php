<?php

namespace App\Providers;

use App\Utils\Snowflake\RandomSequenceResolver;
use App\Utils\Snowflake\Snowflake;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        $this->app->singleton('snowflake', function () {
            return (new Snowflake())
                ->setStartTimeStamp(strtotime('2000-10-10')*1000)
                ->setSequenceResolver(new RandomSequenceResolver());
        });
    }
}
