<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingInformationIdToCheckoutTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkout_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('billing_information_id')->nullable()->after('user_id');
            $table->foreign('billing_information_id')->references('id')->on('billing_information')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkout_transactions', function (Blueprint $table) {
            $table->dropColumn('billing_information_id');
            $table->dropForeign(['billing_information_id']);
        });
    }
}
