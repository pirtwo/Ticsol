<?php

use Illuminate\Database\Seeder;
use App\Ticsol\Components\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'client_id' => 1,
            'name' => "ticsol worker",
            'firstname' => "ticsol",
            'lastname' => "worker",
            'email' => "email@ticsol.com",
            'password' => bcrypt('secret'),              
            'meta' => []
        ]);

        factory(User::class, 30)->create();
    }
}
