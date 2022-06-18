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
            $table->string('pic_title', 30);
            $table->enum('pic_visibility', ['Publico', 'Privado']);
            $table->string('pic_url_image');
            $table->unsignedBigInteger('use_id');
            $table->unsignedBigInteger('cat_id');
            $table->binary('sonido')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('use_id')->references('id')->on('users');
            $table->foreign('cat_id')->references('id')->on('categories');            
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
