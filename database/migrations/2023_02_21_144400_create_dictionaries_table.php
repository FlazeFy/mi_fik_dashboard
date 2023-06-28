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
        Schema::create('dictionaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_name', 85);
            $table->string('dct_name', 75);
            $table->string('dct_desc', 255)->nullable();
            $table->string('dct_type', 75);

            $table->dateTime('created_at', $precision = 0);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->dateTime('deleted_at', $precision = 0)->nullable();
            $table->string('created_by', 36);
            $table->string('updated_by', 36)->nullable();
            $table->string('deleted_by', 36)->nullable();

            $table->foreign('dct_type')->references('id')->on('dictionaries_types');
            $table->foreign('created_by')->references('id')->on('admins');
            $table->foreign('updated_by')->references('id')->on('admins');
            $table->foreign('deleted_by')->references('id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dictionaries');
    }
};
