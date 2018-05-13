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
            $table->increments('passreset_id');
            $table->unsignedInteger('user_id');
            
            // Attributes
            $table->string('passreset_email')->unique();
            $table->string('passreset_token');
            $table->timestamps();
        });
        
        Schema::table('ts_password_reset', function (Blueprint $table) {
            
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
        Schema::table('ts_password_reset', function (Blueprint $table) {
            //
        });
    }
}
