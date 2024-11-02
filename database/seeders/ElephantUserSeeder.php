<?php

namespace Database\Seeders;

use App\Models\Elephant;
use App\Models\User;
use Illuminate\Database\Seeder;

class ElephantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() : void
    {
        $users = User::factory()->count(10)->create();

        $elephants = Elephant::factory()->count(30)->create();

        foreach ($users as $user) {
            $elephantIds = $elephants->random(rand(1, 2))->pluck('id');

            // Update the user_id for the selected elephants
            Elephant::whereIn('id', $elephantIds)->update(['user_id' => $user->id]);
        }
    }
}
