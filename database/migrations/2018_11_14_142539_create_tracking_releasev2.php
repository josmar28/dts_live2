<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingReleasev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tracking_releasev2')){
            return true;
        }
        Schema::create('tracking_releasev2', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('released_by');
            $table->integer('released_section_to');
            $table->dateTime('released_date');
            $table->string('remarks');
            $table->integer('document_id');
            $table->string('route_no');
            $table->string('status');
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
        Schema::create('tracking_releasev2');
    }
}
