<?php

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
        $user = User::all()->count();
        if($user == 0) {
            User::create(
                [
                    "name" => "StuDocu",
                    "email" => "test@studocu.com"
                ]
            );
        }
    }
}
