<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_invitations', function (Blueprint $table) {
            
            // Keys
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id')
                ->nullable();
            
            // Attributes
            $table->string('email');
            $table->string('token');
            $table->timestamps();
        });
        
        Schema::table('ts_invitations', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_invitations', function (Blueprint $table) {
            //
        });
    }
}
