<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestsTableSeeder extends Seeder
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
            'work_id' => '1',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '2',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '3',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '4',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '5',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '6',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '7',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '8',
            'start_time'=> '12:00',
            'end_time' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'user_id' => '1',
            'work_id' => '8',
            'start_time'=> '15:00',
            'end_time' => '15:30',
        ];
        DB::table('rests')->insert($pram);
    }
}
