<?php

namespace Database\Seeders;

use App\Models\Elephant;
use Illuminate\Database\Seeder;

class ElephantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        Elephant::factory()->count(10)->create();
    }
}
