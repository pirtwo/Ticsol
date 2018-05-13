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
            $table->increments('address_id');
            $table->unsignedInteger('contact_id');

            // Attributes
            $table->string('address_unit');
            $table->string('address_street');
            $table->string('address_subrb');
            $table->string('address_country');
            $table->string('address_postcode');            
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_addresses', function (Blueprint $table) {
            
            $table->foreign('contact_id')
                ->references('contact_id')
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
