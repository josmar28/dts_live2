<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrrSupply extends Migration
{

    public function up()
    {
        if(Schema::hasTable('prr_supply')){
            return true;
        }
        Schema::create('prr_supply', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_no');
            $table->text('prr_logs_key');
            $table->integer('qty');
            $table->text('issue');
            $table->text('description');
            $table->text('specification');
            $table->text('unit_cost');
            $table->text('estimated_cost');
            $table->boolean('status');
            $table->rememberToken();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::drop('prr_supply');
    }
}
