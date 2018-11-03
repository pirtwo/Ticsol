<?php

use Illuminate\Database\Seeder;
use App\Ticsol\Components\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'full'
        ]);

        Permission::create([
            'name' => 'view'
        ]);

        Permission::create([
            'name' => 'view_all'
        ]);

        Permission::create([
            'name' => 'create'
        ]);

        Permission::create([
            'name' => 'update'
        ]);

        Permission::create([
            'name' => 'update_all'
        ]);

        Permission::create([
            'name' => 'delete'
        ]);  
        
        Permission::create([
            'name' => 'delete_all'
        ]);  
    }
}
