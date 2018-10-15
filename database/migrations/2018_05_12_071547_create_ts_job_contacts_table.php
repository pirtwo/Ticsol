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
        
        Schema::create('ts_job_contact', function (Blueprint $table) {
            
            // Keys
            $table->unsignedInteger('job_id');
            $table->unsignedInteger('contact_id');            

            // Attributes
            $table->string('type')
                ->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_job_contact', function (Blueprint $table) {
            
            $table->foreign('job_id')
                ->references('id')
                ->on('ts_jobs')
                ->onDelete('cascade');

            $table->foreign('contact_id')
                ->references('id')
                ->on('ts_contacts')
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
        Schema::table('ts_job_contact', function (Blueprint $table) {
            //
        });
    }
}
