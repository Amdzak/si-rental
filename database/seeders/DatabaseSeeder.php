<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            MachineSeeder::class,
            SparepartSeeder::class,
            ScheduleSeeder::class,
            DamageReportSeeder::class,
            RepairReportSeeder::class
        ]);
    }
}