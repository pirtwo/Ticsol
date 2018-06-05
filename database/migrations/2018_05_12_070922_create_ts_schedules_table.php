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
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('job_id');     
            
            // Attributes
            $table->string('type');  
            $table->string('status');                      
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('offsite');
            $table->time('break_length');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_schedules', function (Blueprint $table) {            
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('job_id')
                ->references('id')
                ->on('ts_jobs')
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
        Schema::table('ts_schedules', function (Blueprint $table) {
            //
        });
    }
}