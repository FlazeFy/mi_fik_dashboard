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
        Schema::create('archives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_name', 255);
            $table->string('archive_name', 75);
            $table->string('archive_desc', 255)->nullable();

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 75);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->string('updated_by', 75)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
    }
};
