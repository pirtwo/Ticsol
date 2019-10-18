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
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('creator_id');
            $table->unsignedInteger('parent_id')
                ->nullable();

            // Attributes
            $table->string('name');
            $table->string('type')
                ->nullable();
            $table->boolean("billable")
                ->default(false);
            $table->json('schema')
                ->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_forms', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('ts_users');

            $table->foreign('parent_id')
                ->references('id')
                ->on('ts_forms');
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
