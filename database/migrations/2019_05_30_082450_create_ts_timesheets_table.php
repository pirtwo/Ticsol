<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsTimesheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('ts_timesheets', function (Blueprint $table) {
            // Keys
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id')
                ->nullable();
            $table->unsignedInteger('request_id');

            // Attribuets
            $table->dateTime('week_start');
            $table->dateTime('week_end');
            $table->time('total_hours');
            $table->softDeletes();
            $table->timestamps();
        });

        
        Schema::table('ts_timesheets', function(Blueprint $table) {
            $table->foreign('client_id')
                ->refrence('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->refrence('id')
                ->on('ts_users');
            
            $table->foreign('request_id')
                ->refrence('id')
                ->on('ts_requests');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_timesheets', function (Blueprint $table) {
            //
        });
    }
}
