<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_banks', function (Blueprint $table) {
            
            // Keys
            $table->increments('bank_id');
            $table->unsignedInteger('contact_id');
            
            // Attributes
            $table->string('bank_bsb');
            $table->string('bank_acc_name');
            $table->string('bank_acc_number');
            $table->string('bank_split');            
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_banks', function (Blueprint $table) {
            
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
        Schema::table('ts_banks', function (Blueprint $table) {
            //
        });
    }
}
