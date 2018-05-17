<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_users', function (Blueprint $table) {
            
            // Keys
            $table->increments('user_id');
            $table->unsignedInteger('client_id');

            // Attributes
            $table->string('user_name');
            $table->string('user_email')
                ->unique();
            $table->string('user_password');
            $table->boolean('user_isowner');
            $table->json('user_settings')
                ->nullable();            
            $table->softDeletes();
            $table->timestamps();           
        });
        

        Schema::table('ts_users', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('client_id')
                ->on('ts_clients')
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
        Schema::table('ts_users', function (Blueprint $table) {
            //
        });
    }
}
