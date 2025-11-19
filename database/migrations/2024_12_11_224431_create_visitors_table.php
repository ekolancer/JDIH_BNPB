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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // Untuk IPv4 dan IPv6
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->string('path')->nullable();
            $table->string('referrer')->nullable();
            $table->boolean('suspicious')->default(false);
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });               
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
