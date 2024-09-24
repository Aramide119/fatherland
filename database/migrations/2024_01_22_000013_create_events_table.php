<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('location');
            $table->longText('description');
            $table->string('tags')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('discount');
            $table->unsignedBigInteger('event_category_id');
            $table->foreign('event_category_id')->references('id')->on('event_categories');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
