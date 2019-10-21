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
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedBigInteger('request_id');

            // Attribuets
            $table->string('year');
            $table->integer('week_number');
            $table->date('week_start');
            $table->date('week_end');
            $table->time('total_hours');

            // json
            $table->json('billing')->nullable(); 
            
            $table->softDeletes();
            $table->timestamps();
        });

        
        Schema::table('ts_timesheets', function(Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users');
            
            $table->foreign('request_id')
                ->references('id')
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
