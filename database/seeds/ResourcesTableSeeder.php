<?php

use Illuminate\Database\Seeder;
use App\Ticsol\Components\Models\Resource;

class ResourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Resource::create([
            'name' => 'client'
        ]);

        Resource::create([
            'name' => 'user'
        ]);

        Resource::create([
            'name' => 'role'
        ]);

        Resource::create([
            'name' => 'acl'
        ]);

        Resource::create([
            'name' => 'schedule'
        ]);

        Resource::create([
            'name' => 'timesheet'
        ]);

        Resource::create([
            'name' => 'activity'
        ]);

        Resource::create([
            'name' => 'request'
        ]);

        Resource::create([
            'name' => 'job'
        ]);

        Resource::create([
            'name' => 'job_profile'
        ]);

        Resource::create([
            'name' => 'invitation'
        ]);

        Resource::create([
            'name' => 'contact'
        ]);

        Resource::create([
            'name' => 'address'
        ]);

        Resource::create([
            'name' => 'bank'
        ]);

        Resource::create([
            'name' => 'xero'
        ]);

    }
}
