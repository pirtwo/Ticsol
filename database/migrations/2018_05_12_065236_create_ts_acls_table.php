<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTsAclsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('ts_acls', function (Blueprint $table) {
            
            // Keys
            $table->increments('acl_id')
                ->primary();
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('resource_id');
            $table->unsignedInteger('permission_id');

            // Attributes
            $table->softDeletes();
            $table->timestamps();            
        });
        
        Schema::table('ts_acls', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('client_id')
                ->on('ts_clients')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('ts_users')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('role_id')
                ->on('ts_roles')
                ->onDelete('cascade');

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
        Schema::table('ts_acls', function (Blueprint $table) {
            //
        });
    }
}
