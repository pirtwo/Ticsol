<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_teams', function (Blueprint $table) {

            // Keys
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');

            // Attributes
            $table->string('name');

            $table->softDeletes();
            $table->timestamps();
        });

        
        Schema::table('ts_teams', function(Blueprint $table) {

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
        Schema::dropIfExists('ts_teams');
    }
}
