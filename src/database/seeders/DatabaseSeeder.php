<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);

        $this->call(WorksTableSeeder::class);

        $this->call(RestsTableSeeder::class);

        $this->call(AdminsTableSeeder::class);
    }
}
