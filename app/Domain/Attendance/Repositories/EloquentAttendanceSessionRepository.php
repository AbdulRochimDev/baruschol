<?php

namespace App\Domain\Attendance\Repositories;

use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;

class EloquentAttendanceSessionRepository implements AttendanceSessionRepositoryInterface
{
    public function create(array $data): int
    {
        $s = AttendanceSession::create([
            'class_subject_id' => $data['class_subject_id'],
            'status' => $data['status'] ?? 'draft',
            'date' => $data['date'] ?? now()->toDateString(),
        ]);
        return $s->id;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $s = AttendanceSession::find($id);
        if (! $s) return false;
        $s->status = $status;
        return $s->save();
    }

    public function upsertRecord(int $sessionId, int $studentId, string $status): void
    {
        AttendanceRecord::updateOrCreate(
            ['attendance_session_id' => $sessionId, 'student_id' => $studentId],
            ['status' => $status]
        );
    }
}
