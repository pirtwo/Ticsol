<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_comments', function (Blueprint $table) {
            
            // Keys
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('job_id')
                ->nullable();
            $table->unsignedInteger('parent_id')
                ->nullable();
            $table->unsignedInteger('request_id')
                ->nullable();
            
            // Attributes
            $table->string('body');            
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
            
            $table->foreign('job_id')
                ->references('id')
                ->on('ts_jobs')
                ->onDelete('cascade');  

            $table->foreign('parent_id')
                ->references('id')
                ->on('ts_comments')
                ->onDelete('cascade');  

            $table->foreign('request_id')
                ->references('id')
                ->on('ts_requests')
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
        Schema::table('ts_comments', function (Blueprint $table) {
            //
        });
    }
}
