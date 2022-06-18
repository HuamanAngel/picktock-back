<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePictogramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictogramas', function (Blueprint $table) {
            $table->id();
            $table->string('pic_title');
            $table->string('visibility');
            $table->string('pic_url_image');
            $table->unsignedBigInteger('use_id');
            $table->foreign('use_id')->references('id')->on('users');
            $table->unsignedBigInteger('cat_id');
            $table->foreign('cat_id')->references('id')->on('categories');
            $table->timestamps();
            $table->string('sub_cat_id')->nullable();
            $table->boolean('pic_pressed_for_kid');
            $table->timestamp('pic_pressed_date')->nullable();
            $table->double('pic_average_calification', 8, 2);
            $table->integer('pic_calification_count');
            $table->string('pic_sonido')->nullable();
            $table->string('pic_sonido_public_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pictogramas');
    }
}
