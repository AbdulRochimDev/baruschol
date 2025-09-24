<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LedgersSeeder extends Seeder
{
    public function run()
    {
        DB::table('ledgers')->updateOrInsert(['id' => 1], [
            'name' => 'General Ledger',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
