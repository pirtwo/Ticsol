<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_permissions', function (Blueprint $table) {
            
            // Keys
            $table->increments('id');

            // Attributes
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_permissions', function (Blueprint $table) {
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
        Schema::table('ts_permissions', function (Blueprint $table) {
            //
        });
    }
}
