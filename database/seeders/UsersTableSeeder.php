<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        $user=User::first();
        $user->name="summer";
        $user->email="summer@example.com";
        $user->avatar=config("app.url")."/"."head-default.jpeg";
        $user->save();
    }
}
