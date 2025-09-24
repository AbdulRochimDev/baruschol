<?php

namespace App\Domain\Attendance\Repositories;

interface AttendanceSessionRepositoryInterface
{
    public function create(array $data): int;
    public function updateStatus(int $id, string $status): bool;
    public function upsertRecord(int $sessionId, int $studentId, string $status): void;
}
