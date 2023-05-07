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
        Schema::create('users_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group_name', 75);
            $table->string('group_desc', 255);

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);
            $table->dateTime('updated_at', $precision = 0)->nullable();
            $table->string('updated_by', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_groups');
    }
};
