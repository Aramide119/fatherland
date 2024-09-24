<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('link')->unique();
            $table->string('name');
            $table->string('location');
            $table->string('current_location');
            $table->string('notable_individual');
            $table->text('about');
            $table->string('profile_picture')->nullable();
            $table->string('cover_picture')->nullable();
            $table->string('status');
            // $table->string('reference')->nullable();
            $table->string('reference_link')->nullable();
            $table->string('account_type');
            $table->string('invite_token');
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
        Schema::dropIfExists('families');
    }
}
