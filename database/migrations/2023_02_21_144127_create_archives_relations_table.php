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
            $table->string('archive_id', 75);
            $table->string('content_id', 75);
            
            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 75);
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
