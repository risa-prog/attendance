<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

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
              'email' =>'taro@gmail.com',
              'password' => Hash::make('taro9999'),
            'email_verified_at' => Carbon::now(),
          ];
          DB::table('users')->insert($pram);

        $pram=[
              'name' => '山田花子',
              'email' =>'hanako@gmail.com',
              'password' => Hash::make('hanako875'),
            'email_verified_at' => Carbon::now(),
          ];
          DB::table('users')->insert($pram);

      $pram = [
        'name' => '山田次郎',
        'email' => 'jiro@gmail.com',
        'password' => Hash::make('jiro2222'),
        'email_verified_at' => Carbon::now(),
      ];
      DB::table('users')->insert($pram);

    $pram = [
      'name' => '山田三郎',
      'email' => 'saburo@gmail.com',
      'password' => Hash::make('saburo333'),
      'email_verified_at' => Carbon::now(),
    ];
    DB::table('users')->insert($pram);
  }
}
