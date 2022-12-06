<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class JustLetterDoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('just_letter_doc')){
            return true;
        }
        Schema::create('just_letter_doc', function (Blueprint $table){
            $table->increments('justid');
            $table->string('route_no');
            $table->string('msg_body',1000);
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
        Schema::drop('just_letter_doc');
    }
}
