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
        $user = new User();
        $user->fill([
            'client_id' => 1,
            'firstname' => "ticsol",
            'lastname' => "worker",
            'email' => "ahmad.f1111@gmail.com",
            'password' => bcrypt('secret'),  
            'settings' => [],            
            'meta' => []
        ]);
        $user->isowner = true;
        $user->save();        
    }
}
