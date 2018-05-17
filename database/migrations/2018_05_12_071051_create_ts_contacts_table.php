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
            $table->increments('contact_id');
            $table->unsignedInteger('user_id')
                ->nullable();

            // Attributes
            $table->string('contact_group');
            $table->string('contact_firstname');
            $table->string('contact_lastname');
            $table->string('contact_telephone');
            $table->string('contact_mobilephone');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_contacts', function (Blueprint $table) {
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
        Schema::table('ts_contacts', function (Blueprint $table) {
            //
        });
    }
}
