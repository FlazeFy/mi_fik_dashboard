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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            // original

            // $table->id();
            // $table->morphs('tokenable');
            // $table->string('name');
            // $table->string('token', 64)->unique();
            // $table->text('abilities')->nullable();
            // $table->timestamp('last_used_at')->nullable();
            // $table->timestamp('expires_at')->nullable();
            // $table->timestamps();

            $table->uuid('id')->primary();
            $table->string('tokenable_type', 75);
            $table->string('tokenable_id', 75);
            $table->string('name', 500);
            $table->string('token', 500)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
