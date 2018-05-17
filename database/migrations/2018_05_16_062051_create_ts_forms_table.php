<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_forms', function (Blueprint $table) {
            
            // Keys
            $table->increments('form_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('user_id');

            // Attributes
            $table->string('form_name');
            $table->json('form_body');
            $table->json('form_values');
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_forms', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('client_id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('ts_users')
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
        Schema::table('ts_forms', function (Blueprint $table) {
            //
        });
    }
}
