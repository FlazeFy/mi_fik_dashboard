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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('notif_type', 75);
            $table->string('notif_body', 255);
            $table->longText('notif_send_to')->nullable();
            $table->boolean('is_pending');
            $table->dateTime('pending_until', $precision = 0)->nullable();

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->dateTime('deleted_at', $precision = 0)->nullable();
            $table->string('deleted_by', 36)->nullable();

            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('admins')->onDelete('cascade');
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
};
