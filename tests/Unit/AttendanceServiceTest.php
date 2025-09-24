<?php

namespace Tests\Unit;

use App\Domain\Attendance\Services\AttendanceService;
use App\Domain\Attendance\Events\AttendanceSessionClosed;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Event;

class AttendanceServiceTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_closes_session_and_computes_scores()
    {
        $repo = Mockery::mock(\App\Domain\Attendance\Repositories\AttendanceSessionRepositoryInterface::class);
        $scoreService = Mockery::mock(\App\Domain\Attendance\Services\AttendanceScoreService::class);

        $repo->shouldReceive('updateStatus')->with(123, 'closed')->once()->andReturn(true);
        $scoreService->shouldReceive('computeForSession')->with(123)->once()->andReturn(['42' => ['score' => 1]]);

        // intercept event dispatch
        Event::fake();

        $service = new AttendanceService($repo, $scoreService);
        $res = $service->closeSession(123);

        $this->assertEquals(['42' => ['score' => 1]], $res);
        Event::assertDispatched(AttendanceSessionClosed::class);
    }
}
