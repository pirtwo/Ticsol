<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_resources', function (Blueprint $table) {
            
            // Keys
            $table->increments('resource_id')
                ->primary();

            // Attributes
            $table->string('resource_name');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_resources', function (Blueprint $table) {
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
        Schema::table('ts_resources', function (Blueprint $table) {
            //
        });
    }
}
