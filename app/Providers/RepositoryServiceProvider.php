<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AppointmentInterface;
use App\Repositories\AppointmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AppointmentInterface::class, AppointmentRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
