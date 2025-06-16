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
            'date' => '2025-05-01',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-05-05',
            'work_start' => '9:00',
            'work_end' => '17:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-05-08',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-04-04',
            'work_start' => '9:00',
            'work_end' => '17:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-04-09',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-03-04',
            'work_start' => '9:00',
            'work_end' => '17:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-03-08',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-02-04',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '2',
            'date' => '2025-05-18',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '1',
            'date' => '2025-02-04',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '2',
            'date' => Carbon::now()->format('Y-m-d'),
            'work_start' => '9:00',
            'work_end' => null,
            'status' => '1'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '3',
            'date' => Carbon::now()->format('Y-m-d'),
            'work_start' => '9:00',
            'work_end' => null,
            'status' => '2'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '4',
            'date' => Carbon::now()->format('Y-m-d'),
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);

        $pram = [
            'user_id' => '4',
            'date' => '2025-06-10',
            'work_start' => '9:00',
            'work_end' => '18:00',
            'status' => '3'
        ];
        DB::table('works')->insert($pram);
    }
}
