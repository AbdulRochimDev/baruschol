<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceCreateRequest;
use App\Http\Requests\AttendanceRecordsRequest;
use App\Domain\Attendance\Services\AttendanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class AttendanceController extends Controller
{
    public function create(AttendanceCreateRequest $req, AttendanceService $service): JsonResponse
    {
        Gate::authorize('isRole', 'guru');

        $payload = $req->validated();
        $dto = \App\Domain\Attendance\DTOs\CreateAttendanceSessionDTO::fromArray($payload);
        $id = $service->createSession($dto);

        return response()->json(['id' => $id], 201);
    }

    public function open($id, AttendanceService $service): JsonResponse
    {
        Gate::authorize('isRole', 'guru');
        $service->openSession((int)$id);
        return response()->json(['status'=>'open']);
    }

    public function records(AttendanceRecordsRequest $req, $id, AttendanceService $service): JsonResponse
    {
        Gate::authorize('isRole', 'guru');
        $data = $req->validated();
        // bulk upsert attendance_records
        $service->recordBulk((int)$id, $data['records']);
        return response()->json(['ok'=>true]);
    }

    public function close($id, AttendanceService $service): JsonResponse
    {
        Gate::authorize('isRole', 'guru');
        $results = $service->closeSession((int)$id);
        return response()->json(['status'=>'closed','results'=>$results]);
    }
}
