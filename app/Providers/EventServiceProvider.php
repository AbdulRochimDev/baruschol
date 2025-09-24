<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // 'App\Events\AttendanceSessionClosed' => [
        //     'App\Listeners\AttendanceScoreListener',
        // ],
        // 'App\Events\GradesUpdated' => [
        //     'App\Listeners\ReportCardSyncListener',
        // ],
        // 'App\Events\PaymentVerified' => [
        //     'App\Listeners\LedgerPostingListener',
        // ],
        // 'App\Events\PpdbVerified' => [
        //     'App\Listeners\StudentAccountListener',
        // ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
