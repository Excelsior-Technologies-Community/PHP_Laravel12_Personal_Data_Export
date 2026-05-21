<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('exports', 'file_size')) {
                $table->bigInteger('file_size')->nullable()->after('file_name');
            }
            if (!Schema::hasColumn('exports', 'format')) {
                $table->string('format')->default('json')->after('file_size');
            }
            if (!Schema::hasColumn('exports', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('exported_at');
            }
            if (!Schema::hasColumn('exports', 'is_bulk')) {
                $table->boolean('is_bulk')->default(false)->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('exports', function (Blueprint $table) {
            $table->dropColumn(['file_size', 'format', 'ip_address', 'is_bulk']);
        });
    }
};