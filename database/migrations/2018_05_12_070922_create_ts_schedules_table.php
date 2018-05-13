<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_schedules', function (Blueprint $table) {
            
            // Keys
            $table->increments('schedule_id')
                ->primary();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('job_id');     
            
            // Attributes
            $table->string('schedule_status');
            $table->string('schedule_type');
            $table->string('schedule_event_type');
            $table->dateTime('schedule_start');
            $table->dateTime('schedule_end');
            $table->integer('schedule_offsite');
            $table->integer('schedule_breake_length');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_schedules', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_schedules', function (Blueprint $table) {
            //
        });
    }
}
