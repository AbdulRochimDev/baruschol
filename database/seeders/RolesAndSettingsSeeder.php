<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolesAndSettingsSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Akademik', 'slug' => 'akademik'],
            ['name' => 'Keuangan', 'slug' => 'keuangan'],
            ['name' => 'Operator PPDB', 'slug' => 'operator-ppdb'],
            ['name' => 'Guru', 'slug' => 'guru'],
            ['name' => 'Wali Kelas', 'slug' => 'wali-kelas'],
            ['name' => 'Siswa', 'slug' => 'siswa'],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['slug' => $r['slug']], $r + ['created_at' => now(),'updated_at'=>now()]);
        }

        $settings = [
            ['key'=>'attendance_weight','value'=>'{"present":1,"absent":0,"sick":0.5,"excused":0.75}'],
            ['key'=>'default_term','value'=>'1'],
        ];

        foreach ($settings as $s) {
            DB::table('settings')->updateOrInsert(['key'=>$s['key']], $s + ['created_at'=>now(),'updated_at'=>now()]);
        }
        
            // ...existing code...
    }
}
