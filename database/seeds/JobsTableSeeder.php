<?php

use Illuminate\Database\Seeder;
use App\Ticsol\Components\Models\Job;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Job::class, 50)->create();
    }
}
