<?php

use Illuminate\Database\Seeder;
use App\Ticsol\Components\Models\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'name' => "ticsol",
            'meta' => [],
        ]);
    }
}
