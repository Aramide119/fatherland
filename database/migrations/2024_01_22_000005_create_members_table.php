<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('email')->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->date('date_of_birth');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('membership_id')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('career_status')->nullable();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
