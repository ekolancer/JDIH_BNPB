<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Composite index untuk optimasi query duplikasi harian di VisitorCounter middleware.
     * Query: WHERE ip_address = ? AND DATE(visited_at) = ?
     */
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->index(['ip_address', 'visited_at'], 'idx_visitors_ip_visited');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex('idx_visitors_ip_visited');
        });
    }
};
