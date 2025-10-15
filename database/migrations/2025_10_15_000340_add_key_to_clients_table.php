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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('key', 50)->unique()->nullable()->after('code');
            $table->timestamp('last_accessed_at')->nullable()->after('is_active');
            $table->integer('access_count')->default(0)->after('last_accessed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['key', 'last_accessed_at', 'access_count']);
        });
    }
};
