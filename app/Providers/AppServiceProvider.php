<?php

namespace App\Providers;

use App\Repositories\AppointmentRepository;
use App\Repositories\HealthcareProfessionalRepository;
use App\Repositories\UserRepository;
use App\Services\AppointmentService;
use App\Services\AuthService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        $this->app->bind(HealthcareProfessionalRepository::class, function ($app) {
            return new HealthcareProfessionalRepository();
        });

        $this->app->bind(AppointmentRepository::class, function ($app) {
            return new AppointmentRepository();
        });

        $this->app->bind(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(UserRepository::class)
            );
        });

        $this->app->bind(AppointmentService::class, function ($app) {
            return new AppointmentService(
                $app->make(AppointmentRepository::class),
                $app->make(HealthcareProfessionalRepository::class)
            );
        });
    }
}
