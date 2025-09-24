<?php

namespace Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    public function createApplication(): \Illuminate\Foundation\Application
    {

    $app = require __DIR__.'/../bootstrap/app.php';

    // Ensure Facades are aware of the application during tests
    \Illuminate\Support\Facades\Facade::setFacadeApplication($app);

    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
