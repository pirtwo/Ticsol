<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestsForeignKeyToSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_schedules', function (Blueprint $table) {
            
            $table->unsignedInteger('request_id')
                ->after('job_id')
                ->nullable();

            $table->unsignedInteger('parent_id')
                ->after('request_id')
                ->nullable();

            $table->foreign('request_id')
                ->references('id')
                ->on('ts_requests')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
                ->on('ts_schedules')
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
