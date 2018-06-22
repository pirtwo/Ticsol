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
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('parent_id')
                ->nullable();
            $table->unsignedInteger('form_id')
                ->nullable();

            // Attributes
            $table->string('title');
            $table->string('code');
            $table->boolean('isactive');
            $table->json('meta')
                ->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_jobs', function (Blueprint $table) {
            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');   

            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
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
