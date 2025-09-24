<?php

namespace App\Domain\Attendance\Repositories;

use Illuminate\Support\Facades\DB;

class DbAttendanceSessionRepository implements AttendanceSessionRepositoryInterface
{
    public function create(array $data): int
    {
        return DB::table('attendance_sessions')->insertGetId([
            'class_subject_id' => $data['class_subject_id'],
            'status' => $data['status'] ?? 'draft',
            'date' => $data['date'] ?? now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        return (bool) DB::table('attendance_sessions')->where('id', $id)->update(['status' => $status, 'updated_at' => now()]);
    }

    public function upsertRecord(int $sessionId, int $studentId, string $status): void
    {
        DB::table('attendance_records')->updateOrInsert(
            ['attendance_session_id' => $sessionId, 'student_id' => $studentId],
            ['status' => $status, 'updated_at' => now(), 'created_at' => now()]
        );
    }
}
