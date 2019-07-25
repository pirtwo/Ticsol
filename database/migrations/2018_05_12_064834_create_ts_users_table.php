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
            $table->increments('id');
            $table->unsignedInteger('client_id');

            // Attributes
            $table->string('name');
            $table->string("firstname");
            $table->string("lastname");
            $table->string('email')
                ->unique();
            $table->string('password');  
            $table->rememberToken();          
            $table->boolean('isowner')
                ->default(false);
            $table->boolean('isactive')
                ->default(true);
            $table->json('meta')
                ->nullable();            
            $table->softDeletes();
            $table->timestamps();           
        });
        

        Schema::table('ts_users', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
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
