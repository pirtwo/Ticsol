<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_requests', function (Blueprint $table) {
           
            // Keys
            $table->increments('request_id')
                ->primary();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('assigned_id');

            // Attributes
            $table->string('request_type');
            $table->string('request_status');    
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_requests', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('user_id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('assigned_id')
                ->references('user_id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('job_id')
                ->references('job_id')
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
        Schema::table('ts_requests', function (Blueprint $table) {
            //
        });
    }
}
