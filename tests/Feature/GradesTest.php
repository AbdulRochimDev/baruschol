<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);
});

it('upserts grades and triggers report card sync', function () {
    $classId = DB::table('classes')->insertGetId(['name'=>'Kelas 2','created_at'=>now(),'updated_at'=>now()]);
    $subjectId = DB::table('subjects')->insertGetId(['name'=>'Bahasa','created_at'=>now(),'updated_at'=>now()]);
    $teacherUser = DB::table('users')->insertGetId(['name'=>'Guru2','email'=>'guru2@example.test','password'=>'x','created_at'=>now(),'updated_at'=>now()]);
    // attach guru role
    DB::table('role_user')->insert(['role_id' => DB::table('roles')->where('slug','guru')->value('id'), 'user_id' => $teacherUser]);
    $teacherId = DB::table('teachers')->insertGetId(['user_id'=>$teacherUser,'created_at'=>now(),'updated_at'=>now()]);
    $classSubject = DB::table('class_subjects')->insertGetId(['class_id'=>$classId,'subject_id'=>$subjectId,'teacher_id'=>$teacherId,'created_at'=>now(),'updated_at'=>now()]);

    $gradeItem = DB::table('grade_items')->insertGetId(['class_subject_id'=>$classSubject,'name'=>'Ulangan','weight'=>50,'created_at'=>now(),'updated_at'=>now()]);

    $studentUser = DB::table('users')->insertGetId(['name'=>'Siswa2','email'=>'siswa2@example.test','password'=>'x','created_at'=>now(),'updated_at'=>now()]);
    // attach siswa role
    DB::table('role_user')->insert(['role_id' => DB::table('roles')->where('slug','siswa')->value('id'), 'user_id' => $studentUser]);
    $studentId = DB::table('students')->insertGetId(['user_id'=>$studentUser,'created_at'=>now(),'updated_at'=>now()]);

    $res = $this->actingAs(\App\Models\User::find($teacherUser))->postJson('/api/grades/upsert', [
        'grades' => [ ['grade_item_id' => $gradeItem, 'student_id' => $studentId, 'score' => 85 ] ]
    ]);

    $res->assertStatus(200);
    $res->assertJsonFragment(['ok'=>true]);
});
