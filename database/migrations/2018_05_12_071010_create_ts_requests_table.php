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
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('client_id');            
            $table->unsignedInteger('assigned_id')
                ->nullable();
            $table->unsignedInteger('job_id')
                ->nullable(); 
            $table->unsignedInteger('schedule_id')
                ->nullable(); 
            $table->unsignedInteger('form_id')
                ->nullable();

            // Attributes
            $table->string('type');
            $table->string('status');
            $table->json('meta')
                ->nullable();    
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_requests', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('assigned_id')
                ->references('id')
                ->on('ts_users');

            $table->foreign('job_id')
                ->references('id')
                ->on('ts_jobs');  
                
            $table->foreign('schedule_id')
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
        Schema::table('ts_requests', function (Blueprint $table) {
            //
        });
    }
}
