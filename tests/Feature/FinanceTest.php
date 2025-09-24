<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->seed(\Database\Seeders\DatabaseSeeder::class);
});

it('verifies a payment and posts ledger entry idempotently', function () {
    $studentUser = DB::table('users')->insertGetId(['name'=>'Siswa3','email'=>'siswa3@example.test','password'=>'x','created_at'=>now(),'updated_at'=>now()]);
    // attach siswa role
    DB::table('role_user')->insert(['role_id' => DB::table('roles')->where('slug','siswa')->value('id'), 'user_id' => $studentUser]);
    $studentId = DB::table('students')->insertGetId(['user_id'=>$studentUser,'created_at'=>now(),'updated_at'=>now()]);

    $invoice = DB::table('invoices')->insertGetId(['student_id'=>$studentId,'code'=>'INV-1','amount'=>100000,'created_at'=>now(),'updated_at'=>now()]);
    $payment = DB::table('payments')->insertGetId(['invoice_id'=>$invoice,'amount'=>100000,'status'=>'pending','created_at'=>now(),'updated_at'=>now()]);

    $res = $this->actingAs(\App\Models\User::find($studentUser))->postJson('/api/payments/'.$payment.'/verify');
    $res->assertStatus(200);
    $res->assertJsonFragment(['verified'=>true]);
});
