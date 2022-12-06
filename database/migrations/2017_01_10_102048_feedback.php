<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Feedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('feedback')){
            return true;
        }
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userid');
            $table->string('subject');
            $table->string('telno');
            $table->string('message');
            $table->string('stat_id');
            $table->string('is_read');
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
        \Illuminate\Support\Facades\Schema::drop('feedback');
    }
}
