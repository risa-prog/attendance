<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pram=[
              'name' => '山田次郎',
              'email' =>'yamadaj@gmail.com',
              'password' => Hash::make('jiro8888'),
          ];
          DB::table('admins')->insert($pram);

        $pram = [
            'name' => '山田三郎',
            'email' => 'yamadas@gmail.com',
            'password' => Hash::make('saburo7777'),
        ];
        DB::table('admins')->insert($pram);
    }
}
