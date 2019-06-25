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
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('schedule_id');
            $table->unsignedInteger('job_id');

            // Attributes
            $table->dateTime('from');
            $table->dateTime('till')
                ->nullable();
            $table->mediumText('desc');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_activities', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users');

            $table->foreign('schedule_id')
                ->references('id')
                ->on('ts_schedules')
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
        Schema::table('ts_activities', function (Blueprint $table) {
            //
        });
    }
}
