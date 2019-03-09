<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_addresses', function (Blueprint $table) {
            
            // Keys
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('contact_id');

            // Attributes
            $table->string('unit')
                ->nullable();
            $table->string('number');
            $table->string('street');
            $table->string('suburb');
            $table->string('state');
            $table->string('country');
            $table->string('postcode');            
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_addresses', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');
                
            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('contact_id')
                ->references('id')
                ->on('ts_contacts')
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
        Schema::table('ts_addresses', function (Blueprint $table) {
            //
        });
    }
}
