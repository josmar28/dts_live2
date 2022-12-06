<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('tracking_filter')){
            return true;
        }
        Schema::create('tracking_filter', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doc_type');
            $table->integer('description');
            $table->integer('amount');
            $table->integer('pr_no');
            $table->integer('po_no');
            $table->integer('purpose');
            $table->integer('source_fund');
            $table->integer('requested_by');
            $table->integer('route_to');
            $table->integer('route_from');
            $table->integer('supplier');
            $table->integer('event_date');
            $table->integer('event_location');
            $table->integer('event_participant');
            $table->integer('cdo_applicant');
            $table->integer('cdo_day');
            $table->integer('event_daterange');
            $table->integer('payee');
            $table->integer('item');
            $table->integer('dv_no');
            $table->integer('ors_no');
            $table->integer('fund_source_budget');
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
        Schema::drop('tracking_filter');
    }
}
