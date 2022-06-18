<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalificationUserPictogramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calification_user_pictogramas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pic_id');
            $table->foreign('pic_id')->references('id')->on('pictogramas');
            $table->unsignedBigInteger('use_id');
            $table->foreign('use_id')->references('id')->on('users');
            $table->integer('calification');
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
        Schema::dropIfExists('calification_user_pictogramas');
    }
}
