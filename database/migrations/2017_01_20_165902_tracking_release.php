<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackingRelease extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tracking_release')){
            return true;
        }
        Schema::create('tracking_release', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->integer('reported_by');
            $table->integer('division_id');
            $table->integer('section_id');
            $table->dateTime('date_reported');
            $table->integer('status');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tracking_release');
    }
}
