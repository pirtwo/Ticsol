<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsPasswordResetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_password_reset', function (Blueprint $table) {
            
            // Keys
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('user_id');
            
            // Attributes
            $table->string('email')->unique();
            $table->string('token');
            $table->timestamps();
        });
        
        Schema::table('ts_password_reset', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')
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
        Schema::table('ts_password_reset', function (Blueprint $table) {
            //
        });
    }
}
