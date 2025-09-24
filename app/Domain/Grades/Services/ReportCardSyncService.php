<?php

namespace App\Domain\Grades\Services;

use Illuminate\Support\Facades\DB;

class ReportCardSyncService
{
    public function syncForGradeItem(int $gradeItemId): array
    {
        $gradeItem = DB::table('grade_items')->where('id',$gradeItemId)->first();
        if (! $gradeItem) return [];

        $grades = DB::table('grades')->where('grade_item_id',$gradeItemId)->get();
        $results = [];
        foreach ($grades as $g) {
            // simple: write/update report_card_items for term derived from class_subject->term via joins is omitted
            $results[$g->student_id] = ['score'=>$g->score];
        }
        return $results;
    }
}
