<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('lineage');
            $table->string('location');
            $table->longText('notable_individual');
            $table->longText('about');
            $table->string('reference_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
