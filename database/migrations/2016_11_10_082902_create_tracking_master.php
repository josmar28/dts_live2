<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tracking_master')){
            return true;
        }
        Schema::create('tracking_master', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->string('doc_type');
            $table->dateTime('prepared_date');
            $table->integer('prepared_by');
            $table->integer('division_head');
            $table->text('description');
            $table->float('amount',20,2);
            $table->string('pr_no');
            $table->string('pr_date');
            $table->string('po_no');
            $table->string('po_date');
            $table->string('purpose');
            $table->string('source_fund');
            $table->string('requested_by');
            $table->string('route_to');
            $table->string('route_from');
            $table->string('supplier');
            $table->dateTime('event_date');
            $table->string('event_location');
            $table->string('event_participant');
            $table->string('cdo_applicant');
            $table->integer('cdo_day');
            $table->string('event_daterange');
            $table->string('payee');
            $table->string('item');
            $table->string('dv_no');
            $table->string('ors_no');
            $table->string('fund_source_budget');
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
        Schema::drop('tracking_master');
    }
}
