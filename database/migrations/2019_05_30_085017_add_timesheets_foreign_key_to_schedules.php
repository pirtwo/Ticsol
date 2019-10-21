<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimesheetsForeignKeyToSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('timesheet_id')
                ->after('job_id')
                ->nullable();

            $table->foreign('timesheet_id')
                ->references('id')
                ->on('ts_timesheets')
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
        //
    }
}
