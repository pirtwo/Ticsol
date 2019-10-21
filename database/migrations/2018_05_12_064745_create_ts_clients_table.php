<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_clients', function(Blueprint $table){
            
            // Keys
            $table->increments('id');

            // Attributes
            $table->string('name', 50);
            $table->boolean('isactive')->default(true);

            // json
            $table->json('qbs')->nullable();
            $table->json('settings')->nullable();
            $table->json('billing_settings')->nullable();
            $table->json('billing_defaults')->nullable();
            $table->json('reimbursement_settings')->nullable();
            $table->json('meta')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('ts_clients', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_clients', function (Blueprint $table) {
            //
        });
    }
}
