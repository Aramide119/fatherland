<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynastiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynasties', function (Blueprint $table) {
            $table->id();
            $table->string('link')->unique();
            $table->string('name')->unique();
            $table->string('location')->nullable();
            $table->string('notable_individual')->nullable();
            $table->text('about');
            $table->string('profile_picture')->nullable();
            $table->string('cover_picture')->nullable();
            $table->string('status');
            $table->string('reference')->nullable();
            $table->string('reference_link')->nullable();
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
        Schema::dropIfExists('dynasties');
    }
}
