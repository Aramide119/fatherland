<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('location');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('website_link')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('creator_id');
            $table->string('creator_type');
            $table->string('status')->nullable(); 
            $table->unsignedBigInteger('restaurant_category_id');
            $table->foreign('restaurant_category_id')->references('id')->on('restaurant_categories');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['creator_id', 'creator_type']);
        });
    }
}
