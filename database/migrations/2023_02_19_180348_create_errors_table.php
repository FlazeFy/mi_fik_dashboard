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
        Schema::create('errors', function (Blueprint $table) {
            $table->bigInteger('id')->length(20)->primary();
            $table->text('message');
            $table->text('stack_trace');
            $table->string('file', 25);
            $table->integer('line')->length(11)->unsigned();
            $table->string('faced_by', 36)->nullable();

            $table->timestamp('created_at', $precision = 0);
            $table->timestamp('fixed_at', $precision = 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('errors');
    }
};
