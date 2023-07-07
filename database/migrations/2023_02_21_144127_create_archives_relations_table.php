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
        Schema::create('archives_relations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('archive_id', 36);
            $table->string('content_id', 36);

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);

            // $table->foreign('archive_id')->references('id')->on('archives')->onDelete('cascade');
            // $table->foreign('content_id')->references('id')->on('contents_headers')->onDelete('cascade');
            // $table->foreign('content_id')->references('id')->on('tasks')->onDelete('cascade');
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
        Schema::dropIfExists('archives_relations');
    }
};
