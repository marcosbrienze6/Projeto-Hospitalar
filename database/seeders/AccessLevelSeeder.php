<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('access_level')->insert([
            ['id' => 1, 'name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Editor', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'User', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
