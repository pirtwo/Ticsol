<?php

use Illuminate\Database\Seeder;
use App\Ticsol\Components\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'creator_id' => 1,
            'client_id' => rand(1,3),
        ]);

        Role::create([
            'name' => 'manager',
            'creator_id' => 1, 
            'client_id' => rand(1,3),           
        ]);

        Role::create([
            'name' => 'employee',
            'creator_id' => 1,
            'client_id' => rand(1,3),
        ]);
    }
}
