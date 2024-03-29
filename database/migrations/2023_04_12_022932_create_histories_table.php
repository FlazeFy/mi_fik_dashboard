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
        Schema::create('histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('history_type', 15);
            $table->string('context_id', 36)->nullable();
            $table->string('history_body', 255);
            $table->string('history_send_to', 36)->nullable();
            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);

            // $table->foreign('context_id')->references('id')->on('tasks')->onDelete('cascade');
            // $table->foreign('history_send_to')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
};
