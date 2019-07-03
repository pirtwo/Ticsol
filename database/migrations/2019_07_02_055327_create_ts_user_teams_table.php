<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsUserTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_user_team', function (Blueprint $table) {
            
            // Keys
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('team_id');            

            // Attributes
            $table->softDeletes();
            $table->timestamps();

        });

        
        Schema::table('ts_user_team', function(Blueprint $table) {
            
            $table->foreign('user_id')
                ->references('id')
                ->on('ts_users')
                ->onDelete('cascade');
            
            $table->foreign('team_id')
                ->references('id')
                ->on('ts_teams')
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
        Schema::dropIfExists('ts_user_team');
    }
}
