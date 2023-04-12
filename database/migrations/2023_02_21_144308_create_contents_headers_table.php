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
        Schema::create('contents_headers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug_name', 255);
            $table->string('content_title', 75);
            $table->longText('content_desc')->nullable();
            $table->dateTime('content_date_start', $precision = 0)->nullable();
            $table->dateTime('content_date_end', $precision = 0)->nullable();
            $table->string('content_reminder', 75);
            $table->string('content_image', 255)->nullable();
            $table->boolean('is_draft');

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
        Schema::dropIfExists('contents_headers');
    }
};
