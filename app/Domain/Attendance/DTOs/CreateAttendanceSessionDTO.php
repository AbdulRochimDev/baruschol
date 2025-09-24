<?php

namespace App\Domain\Attendance\DTOs;

class CreateAttendanceSessionDTO
{
    public int $classSubjectId;
    public string $date;

    public function __construct(int $classSubjectId, ?string $date = null)
    {
        $this->classSubjectId = $classSubjectId;
        $this->date = $date ?? now()->toDateString();
    }

    public static function fromArray(array $data): self
    {
        return new self((int) $data['class_subject_id'], $data['date'] ?? null);
    }
}
