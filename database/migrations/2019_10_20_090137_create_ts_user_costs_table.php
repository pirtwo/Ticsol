<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsUserCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_user_costs', function (Blueprint $table) {
            // Keys
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('user_id');

            // Attributes
            $table->float('hourly_rate');
            $table->dateTime('from');

            $table->softDeletes();
            $table->timestamps();           
        });

        Schema::table('ts_user_costs', function (Blueprint $table) {            
            $table->foreign('client_id')
                ->references('id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
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
        Schema::table('ts_user_costs', function (Blueprint $table) {
            //
        });
    }
}
