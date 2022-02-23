<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('parent_1_name')->nullable();
            $table->string('parent_1_phone')->nullable();
            $table->string('parent_2_name')->nullable();
            $table->string('parent_2_phone')->nullable();
            $table->timestamp('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('school')->nullable();
            $table->text('school_info')->nullable();
            $table->text('sign_up_date')->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('users');
    }
}
