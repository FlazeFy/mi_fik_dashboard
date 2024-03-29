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
        Schema::create('contents_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('content_id', 36);
            $table->longText('content_attach')->nullable();
            $table->longText('content_tag');
            $table->longText('content_loc')->nullable();

            $table->dateTime('created_at', $precision = 0);
            $table->dateTime('updated_at', $precision = 0)->nullable();

            $table->foreign('content_id')->references('id')->on('contents_headers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents_details');
    }
};
