<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('content')->nullable();
            $table->unsignedBigInteger('resource_category_id');
            $table->foreign('resource_category_id')->references('id')->on('resource_categories');
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
