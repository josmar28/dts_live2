<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JustLetterHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('just_letter_head')){
            return true;
        }
        Schema::create('just_letter_head', function(Blueprint $table){
            $table->increments('id');
            $table->integer('justid');
            $table->string('head_type');
            $table->string('head_name');
            $table->string('head_desig');
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
        Schema::drop('just_letter_head');
    }
}
