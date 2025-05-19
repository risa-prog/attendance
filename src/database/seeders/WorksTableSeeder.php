<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pram = [
            'user_id' => '1',
            'date' => '2025-05-04',
            'start_time' => '9:00',
            'end_time' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-05-05',
            'start_time' => '9:00',
            'end_time' => '17:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-05-08',
            'start_time' => '9:00',
            'end_time' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-04-04',
            'start_time' => '9:00',
            'end_time' => '17:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-04-09',
            'start_time' => '9:00',
            'end_time' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-03-04',
            'start_time' => '9:00',
            'end_time' => '17:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-03-08',
            'start_time' => '9:00',
            'end_time' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-02-04',
            'start_time' => '9:00',
            'end_time' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '2',
            'date' => '2025-05-18',
            'start_time' => '9:00',
            'end_time' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        
    }
}
