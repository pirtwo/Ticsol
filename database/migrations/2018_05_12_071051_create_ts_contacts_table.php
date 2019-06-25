<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_contacts', function (Blueprint $table) {
            
            // Keys
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('user_id')
                ->nullable();

            // Attributes
            $table->string('group');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('telephone')
                ->nullable();
            $table->string('mobilephone');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_contacts', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade'); 
            
            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users');
            
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
        Schema::table('ts_contacts', function (Blueprint $table) {
            //
        });
    }
}
