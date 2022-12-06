<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrackingReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tracking_report')){
            return true;
        }
        Schema::create('tracking_report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->dateTime('date_reported');
            $table->integer('reported_by');
            $table->string('section_reported');
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
        Schema::drop('tracking_report');
    }
}
