<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_roles', function (Blueprint $table) {
            
            // Keys
            $table->increments('role_id');
            $table->unsignedInteger('user_id');

            // Attributes
            $table->string('role_name');            
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_roles', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('user_id')
                ->on('ts_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_roles', function (Blueprint $table) {
            //
        });
    }
}
