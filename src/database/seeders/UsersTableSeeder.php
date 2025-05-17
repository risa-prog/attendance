<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pram=[
            'name' => '山田太郎',
            'email' =>'yamadat@gmail.com',
            'password' => bcrypt('secret'),
        ];
        DB::table('users')->insert($pram);

        $pram=[
            'name' => '山田花子',
            'email' =>'hanako@gmail.com',
            'password' => bcrypt('secret'),
        ];
        DB::table('users')->insert($pram);

    }
}
