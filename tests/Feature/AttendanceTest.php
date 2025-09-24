<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

beforeEach(function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);
});

it('creates, opens, records and closes an attendance session', function () {
    // create a class_subject and student
    $classId = DB::table('classes')->insertGetId(['name'=>'Kelas 1','created_at'=>now(),'updated_at'=>now()]);
    $subjectId = DB::table('subjects')->insertGetId(['name'=>'Matematika','created_at'=>now(),'updated_at'=>now()]);
    $teacherUser = DB::table('users')->insertGetId(['name'=>'Guru','email'=>'guru@example.test','password'=>'x','created_at'=>now(),'updated_at'=>now()]);
    // attach guru role
    DB::table('role_user')->insert(['role_id' => DB::table('roles')->where('slug','guru')->value('id'), 'user_id' => $teacherUser]);
    $teacherId = DB::table('teachers')->insertGetId(['user_id'=>$teacherUser,'created_at'=>now(),'updated_at'=>now()]);
    $classSubject = DB::table('class_subjects')->insertGetId(['class_id'=>$classId,'subject_id'=>$subjectId,'teacher_id'=>$teacherId,'created_at'=>now(),'updated_at'=>now()]);

    $studentUser = DB::table('users')->insertGetId(['name'=>'Siswa','email'=>'siswa@example.test','password'=>'x','created_at'=>now(),'updated_at'=>now()]);
    // attach siswa role
    DB::table('role_user')->insert(['role_id' => DB::table('roles')->where('slug','siswa')->value('id'), 'user_id' => $studentUser]);
    $studentId = DB::table('students')->insertGetId(['user_id'=>$studentUser,'created_at'=>now(),'updated_at'=>now()]);

    // create attendance session
    $res = $this->actingAs(\App\Models\User::find($teacherUser))->postJson('/api/attendance/sessions', ['class_subject_id'=>$classSubject]);
    $res->assertStatus(201);
    $id = $res->json('id');

    $this->actingAs(\App\Models\User::find($teacherUser))->postJson("/api/attendance/sessions/{$id}/open")->assertStatus(200);

    $this->actingAs(\App\Models\User::find($teacherUser))->postJson("/api/attendance/sessions/{$id}/records", [
        'records' => [ ['student_id' => $studentId, 'status' => 'present'] ]
    ])->assertStatus(200);

    $close = $this->actingAs(\App\Models\User::find($teacherUser))->postJson("/api/attendance/sessions/{$id}/close");
    $close->assertStatus(200);
    $close->assertJsonStructure(['status','results']);
});
