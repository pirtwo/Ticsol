<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_clients', function(Blueprint $table){
            
            // Keys
            $table->increments('client_id')
                ->primary();

            // Attributes
            $table->string('client_name', 50);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('ts_clients', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_clients', function (Blueprint $table) {
            //
        });
    }
}
