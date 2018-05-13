<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_jobs', function (Blueprint $table) {
            
            // Keys
            $table->increments('job_id');
            $table->unsignedInteger('parent_id')
                ->nullable();

            // Attributes
            $table->string('job_title');
            $table->integer('job_code');
            $table->boolean('job_isactive');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_jobs', function (Blueprint $table) {
            $table->foreign('parent_id')
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
        Schema::table('ts_jobs', function (Blueprint $table) {
            //
        });
    }
}
