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
        Schema::create('contents_viewers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('content_id', 36);
            $table->integer('type_viewer')->length(3)->unsigned();

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);

            $table->foreign('content_id')->references('id')->on('contents_headers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents_viewers');
    }
};
