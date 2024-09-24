<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToContentsTable extends Migration
{
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->unsignedBigInteger('content_type_id')->nullable();
            $table->foreign('content_type_id', 'content_type_fk_8883618')->references('id')->on('content_types');
            $table->unsignedBigInteger('content_category_id')->nullable();
            $table->foreign('content_category_id', 'content_category_fk_8883619')->references('id')->on('content_categories');
        });
    }
}
