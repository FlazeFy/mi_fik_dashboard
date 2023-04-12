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
        Schema::create('users_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('tag_slug_name');
            $table->string('request_type', 15);
            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 75);

            $table->boolean('is_rejected')->nullable();
            $table->string('rejected_by', 75)->nullable();
            $table->dateTime('rejected_at', $precision = 0)->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->dateTime('accepted_at', $precision = 0)->nullable();
            $table->string('accepted_by', 75)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_requests');
    }
};
