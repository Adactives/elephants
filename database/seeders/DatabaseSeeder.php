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
        // Call your custom seeders here
        $this->call([
            ElephantUserSeeder::class,
        ]);
    }
}
