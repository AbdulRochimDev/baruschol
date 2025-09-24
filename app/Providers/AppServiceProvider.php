<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Application service registration
        $this->app->bind(
            \App\Domain\Attendance\Repositories\AttendanceSessionRepositoryInterface::class,
            \App\Domain\Attendance\Repositories\EloquentAttendanceSessionRepository::class
        );
        $this->app->bind(\App\Domain\Attendance\Services\AttendanceService::class, function ($app) {
            return new \App\Domain\Attendance\Services\AttendanceService(
                $app->make(\App\Domain\Attendance\Repositories\AttendanceSessionRepositoryInterface::class),
                $app->make(\App\Domain\Attendance\Services\AttendanceScoreService::class)
            );
        });
    }

    public function boot()
    {
        // Application bootstrapping
    }
}
