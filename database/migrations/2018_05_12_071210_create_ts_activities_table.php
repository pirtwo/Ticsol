<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_activities', function (Blueprint $table) {
            
            // Keys
            $table->increments('activity_id')->primary();
            $table->unsignedInteger('schedule_id');

            // Attributes
            $table->dateTime('activity_from');
            $table->dateTime('activity_till');
            $table->dateTime('activity_desc');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_activities', function (Blueprint $table) {
            
            $table->foreign('schedule_id')
                ->references('schedule_id')
                ->on('schedules')
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
        Schema::table('ts_activities', function (Blueprint $table) {
            //
        });
    }
}
