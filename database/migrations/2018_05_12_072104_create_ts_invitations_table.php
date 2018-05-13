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
            $table->increments('invitation_id');
            $table->unsignedInteger('user_id');
            
            // Attributes
            $table->string('invitation_email');
            $table->string('invitation_token');
            $table->timestamps();
        });
        
        Schema::table('ts_invitations', function (Blueprint $table) {
            
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
        Schema::table('ts_invitations', function (Blueprint $table) {
            //
        });
    }
}
