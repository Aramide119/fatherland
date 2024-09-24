<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketTypeAndQuantityToEventOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_type_id')->nullable()->after('event_id');
            $table->foreign('ticket_type_id')->references('id')->on('ticket_types');
            $table->integer('quantity')->nullable()->after('ticket_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_orders', function (Blueprint $table) {
             $table->dropForeign(['ticket_type_id']);
             $table->dropColumn('ticket_type_id');
             $table->dropColumn('quantity');
        });
    }
}
