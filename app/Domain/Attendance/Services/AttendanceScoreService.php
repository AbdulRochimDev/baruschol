<?php

namespace App\Domain\Attendance\Services;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use Illuminate\Support\Facades\DB;

class AttendanceScoreService
{
    /**
     * Calculate and persist a simple attendance score for the session.
     * Returns array with counts and computed score per student.
     */
    public function computeForSession(int $sessionId): array
    {
        // Simple scoring: present=1, absent=0, sick=0.5, excused=0.75
        $weights = [
            'present' => 1,
            'absent' => 0,
            'sick' => 0.5,
            'excused' => 0.75,
        ];

        $records = DB::table('attendance_records')
            ->where('attendance_session_id', $sessionId)
            ->get();

        $results = [];

        foreach ($records as $r) {
            $w = $weights[$r->status] ?? 0;
            $results[$r->student_id] = ['score' => $w, 'status' => $r->status];
        }

        // For demonstration we won't persist into report cards here; return computed results
        return $results;
    }
}
