<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsAccessiblePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('ts_accessible_permissions', function (Blueprint $table) {
            
            // Keys
            $table->unsignedInteger('resource_id');
            $table->unsignedInteger('permission_id');

            // Attributes
            $table->softDeletes();
            $table->timestamps();
        });
        
        Schema::table('ts_accessible_permissions', function (Blueprint $table) {
            
            $table->foreign('resource_id')
                ->references('resource_id')
                ->on('ts_resources')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('permission_id')
                ->on('ts_permissions')
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
        Schema::table('ts_accessible_permissions', function (Blueprint $table) {
            //
        });
    }
}
