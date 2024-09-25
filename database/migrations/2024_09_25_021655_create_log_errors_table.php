<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_errors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0);
            $table->text('exception')->nullable();
            $table->text('message')->nullable();
            $table->integer('line')->nullable();
            $table->json('trace')->nullable();
            $table->string('method')->nullable();
            $table->text('ip')->nullable();
            $table->text('uri')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('header')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_errors');
    }
};
