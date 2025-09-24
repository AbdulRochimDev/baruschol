<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Domain\Grades\Events\GradesUpdated;
use App\Domain\Grades\Services\ReportCardSyncService;

class GradesController extends Controller
{
    public function upsert(Request $req, ReportCardSyncService $syncService)
    {
        Gate::authorize('isRole', 'guru');
        $data = $req->validate([
            'grades' => ['required','array','min:1'],
            'grades.*.grade_item_id' => ['required','integer','exists:grade_items,id'],
            'grades.*.student_id' => ['required','integer','exists:students,id'],
            'grades.*.score' => ['nullable','numeric'],
        ]);

        foreach ($data['grades'] as $g) {
            DB::table('grades')->updateOrInsert(
                ['grade_item_id'=>$g['grade_item_id'],'student_id'=>$g['student_id']],
                ['score'=>$g['score'],'updated_at'=>now(),'created_at'=>now()]
            );
            event(new GradesUpdated($g['grade_item_id']));
        }

        // run sync for last grade item
        $lastId = end($data['grades'])['grade_item_id'];
        $res = $syncService->syncForGradeItem($lastId);
        return response()->json(['ok'=>true,'synced'=>$res]);
    }
}
