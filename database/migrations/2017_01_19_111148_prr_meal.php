<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrrMeal extends Migration
{

    public function up()
    {
        if(Schema::hasTable('prr_meal')){
            return true;
        }
        Schema::create('prr_meal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->text('prr_logs_key');
            $table->text('description');
            $table->integer('expected');
            $table->text('date_time');
            $table->text('unit_cost');
            $table->text('estimated_cost');
            $table->boolean('status');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('prr_meal');
    }
}
