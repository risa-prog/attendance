<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
            'date' => '2025-03-25',
            'work_start' => '9:00:00',
            'work_end' => '18:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-04-08',
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-05-18',
            'work_start' => '9:00:00',
            'work_end' => '18:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-05-24',
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '2',
            'date' => '2025-06-10',
            'work_start' => '9:00:00',
            'work_end' => '18:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-06-10',
            'work_start' => '9:00:00',
            'work_end' => '17:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-06-18',
            'work_start' => '9:00:00',
            'work_end' => '18:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '2',
            'date' => '2025-06-23',
            'work_start' => '9:00:00',
            'work_end' => '18:00:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => Carbon::now()->yesterday()->format('Y-m-d'),
            'work_start' => '9:00:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '2',
            'date' => Carbon::now()->yesterday()->format('Y-m-d'),
            'work_start' => '9:00:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);
    }
}
