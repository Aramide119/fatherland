<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertsTable extends Migration
{
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description');
            $table->string('business_name');
            $table->string('location');
            $table->unsignedBigInteger('advert_category_id');
            $table->foreign('advert_category_id')->references('id')->on('advert_categories');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
