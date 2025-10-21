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
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('path')->nullable()->after('visited_at');
            $table->string('referrer')->nullable()->after('path');
            $table->boolean('suspicious')->default(false)->after('referrer');
            $table->string('country')->nullable()->after('suspicious');
            $table->string('city')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['path', 'referrer', 'suspicious', 'country', 'city']);
        });
    }
};
