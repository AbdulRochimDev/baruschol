<?php

namespace App\Domain\Attendance\Services;

use App\Domain\Attendance\DTOs\CreateAttendanceSessionDTO;
use App\Domain\Attendance\Events\AttendanceSessionClosed;
use App\Domain\Attendance\Repositories\AttendanceSessionRepositoryInterface;

class AttendanceService
{
    public function __construct(
        protected AttendanceSessionRepositoryInterface $repo,
        protected AttendanceScoreService $scoreService
    ) {}

    public function createSession(CreateAttendanceSessionDTO $dto): int
    {
        return $this->repo->create([
            'class_subject_id' => $dto->classSubjectId,
            'date' => $dto->date,
            'status' => 'draft',
        ]);
    }

    public function openSession(int $id): bool
    {
        return $this->repo->updateStatus($id, 'open');
    }

    public function recordBulk(int $sessionId, array $records): void
    {
        foreach ($records as $r) {
            $this->repo->upsertRecord($sessionId, (int)$r['student_id'], $r['status']);
        }
    }

    public function closeSession(int $id): array
    {
        $this->repo->updateStatus($id, 'closed');
        // dispatch domain event
        event(new AttendanceSessionClosed($id));
        return $this->scoreService->computeForSession($id);
    }
}
