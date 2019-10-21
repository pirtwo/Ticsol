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
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedBigInteger('contact_id');
            
            // Attributes
            $table->string('bsb');
            $table->string('acc_name');
            $table->string('acc_number');
            $table->float('split');            
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_banks', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');
                
            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users');
                
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
        Schema::table('ts_banks', function (Blueprint $table) {
            //
        });
    }
}
