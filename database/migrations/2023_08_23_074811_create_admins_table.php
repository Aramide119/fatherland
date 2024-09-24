<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->datetime('email_verified_at')->nullable();
                $table->string('password');
                $table->string('phone_number')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('remember_token')->nullable();
                $table->string('profession')->nullable();
                $table->string('education')->nullable();
                $table->string('location')->nullable();
                $table->text('about')->nullable();
                $table->string('university')->nullable();
                $table->string('professionLocation')->nullable();
                $table->string('profile_picture')->nullable();
                $table->string('cover_picture')->nullable();
                $table->string('account_type')->nullable();
                $table->string('plan_type')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
