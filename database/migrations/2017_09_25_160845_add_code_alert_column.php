<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeAlertColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('tracking_details', function (Blueprint $table) {
            //
            $table->string('code')->after('route_no');
            $table->integer('alert')->after('code');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_details', function (Blueprint $table) {
            //
        });
    }
}
