<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_name', 255);
            $table->string('username', 30);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('first_name', 75);
            $table->string('last_name', 75)->nullable();
            $table->longText('role')->nullable();
            $table->string('image_url', 255)->nullable();
            
            $table->dateTime('created_at', $precision = 0);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->string('updated_by', 75)->nullable();
            $table->dateTime('deleted_at', $precision = 0)->nullable();
            $table->string('deleted_by', 75)->nullable();
            $table->dateTime('accepted_at', $precision = 0)->nullable();
            $table->string('accepted_by', 75)->nullable();
            $table->boolean('is_accepted');
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
};
