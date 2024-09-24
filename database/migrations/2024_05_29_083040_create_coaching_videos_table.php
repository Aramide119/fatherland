<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachingVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaching_videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learning_category_id');
            $table->foreign('learning_category_id')->references('id')->on('learning_categories');
            $table->unsignedBigInteger('coach_id')->constrained();
            $table->foreign('coach_id')->references('id')->on('coaches')->onDelete('cascade');
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
        Schema::dropIfExists('coaching_videos');
    }
}
