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
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_name', 255);
            $table->string('task_title', 75);
            $table->longText('task_desc')->nullable();
            $table->string('task_reminder', 75);
            $table->dateTime('task_date_start', $precision = 0)->nullable();
            $table->dateTime('task_date_end', $precision = 0)->nullable();

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 75);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->string('updated_by', 75)->nullable();
            $table->dateTime('deleted_at', $precision = 0)->nullable();
            $table->string('deleted_by', 75)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
