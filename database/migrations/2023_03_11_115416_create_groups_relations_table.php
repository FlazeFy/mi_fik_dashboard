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
        Schema::create('groups_relations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group_id', 36);
            $table->string('user_id', 36);

            $table->dateTime('created_at', $precision = 0);
            $table->string('created_by', 36);


            $table->foreign('group_id')->references('id')->on('users_groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups_relations');
    }
};
