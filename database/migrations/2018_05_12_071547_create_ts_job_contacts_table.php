<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsJobContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_job_contacts', function (Blueprint $table) {
            
            // Keys
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('job_id');

            // Attributes
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_job_contacts', function (Blueprint $table) {
            
            $table->foreign('contact_id')
                ->references('contact_id')
                ->on('ts_contacts')
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
        Schema::table('ts_job_contacts', function (Blueprint $table) {
            //
        });
    }
}
