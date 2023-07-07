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
            $table->string('created_by', 36);

            $table->boolean('is_rejected')->nullable();
            $table->string('rejected_by', 36)->nullable();
            $table->dateTime('rejected_at', $precision = 0)->nullable();
            $table->boolean('is_accepted');
            $table->dateTime('accepted_at', $precision = 0)->nullable();
            $table->string('accepted_by', 36)->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rejected_by')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('accepted_by')->references('id')->on('admins')->onDelete('cascade');
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
