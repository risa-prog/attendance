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
            'work_id' => '1',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '2',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '3',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '4',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '5',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '6',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '7',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '8',
            'rest_start'=> '12:00',
            'rest_end' => '13:00'
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '8',
            'rest_start'=> '15:00',
            'rest_end' => '15:30',
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '9',
            'rest_start'=> '12:00',
            'rest_end' => '12:45',
        ];
        DB::table('rests')->insert($pram);

        $pram = [
            'work_id' => '9',
            'rest_start'=> '15:00',
            'rest_end' => '15:15',
        ];
        DB::table('rests')->insert($pram);
    }
}
