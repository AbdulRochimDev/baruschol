<?php

namespace App\Domain\Grades\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GradesUpdated
{
    use Dispatchable, SerializesModels;

    public int $gradeItemId;

    public function __construct(int $gradeItemId)
    {
        $this->gradeItemId = $gradeItemId;
    }
}
