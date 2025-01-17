<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); 
            $table->unsignedBigInteger('sender_id');
            $table->string('notification_type');
            $table->foreignId('post_id')->nullable()->constrained();
            $table->foreignId('comment_id')->nullable()->constrained();
            $table->foreignId('family_id')->nullable()->constrained(); 
            $table->foreignId('dynasty_id')->nullable()->constrained('dynasties'); 
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
        Schema::dropIfExists('notifications');
    }
}
