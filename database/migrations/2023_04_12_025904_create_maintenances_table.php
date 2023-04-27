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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('maintenance_type', 75);
            $table->string('maintenance_title', 75);
            $table->string('maintenance_desc', 500);
            $table->string('maintenance_result', 500);

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->string('updated_by', 36)->nullable();
            $table->dateTime('started_at', $precision = 0)->nullable();
            $table->dateTime('finished_at', $precision = 0)->nullable();
            $table->string('started_by', 36)->nullable();
            $table->string('finished_by', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
};
