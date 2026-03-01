<?php

namespace App\Providers;

use App\Faker\ProveedorFakerProvider;
use Faker\Generator;
use Faker\Factory;
use Illuminate\Support\ServiceProvider;

class ProveedorFakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
       $this->app->singleton(Generator::class, function () {
           $faker = Factory::create(env('APP_FAKER_LOCALE'));
           $faker->addProvider(new ProveedorFakerProvider($faker));
           return $faker;
       });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
