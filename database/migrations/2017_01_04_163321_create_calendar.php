<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendar extends Migration
{
    public function up()
    {
        if(Schema::hasTable('calendar')){
            return true;
        }
        Schema::create('calendar', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('start');
            $table->text('backgroundColor');
            $table->text('borderColor');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('calendar');
    }
}
