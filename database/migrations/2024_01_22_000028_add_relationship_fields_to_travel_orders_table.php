<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTravelOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->foreign('travel_id', 'travel_fk_9410358')->references('id')->on('travels');
            $table->unsignedBigInteger('member_id')->nullable();
            $table->foreign('member_id', 'member_fk_9410364')->references('id')->on('members');
        });
    }
}
