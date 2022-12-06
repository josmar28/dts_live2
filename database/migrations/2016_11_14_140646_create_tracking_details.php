<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tracking_details')){
            return true;
        }
        Schema::create('tracking_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->dateTime('date_in');
            $table->integer('received_by');
            $table->integer('delivered_by');
            $table->string('action');
            $table->boolean('status');
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
        Schema::drop('tracking_details');
    }
}
