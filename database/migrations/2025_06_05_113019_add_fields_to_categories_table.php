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
        Schema::table('categories', function (Blueprint $table) {
            // Icon field untuk menyimpan nama icon Bootstrap Icons
            $table->string('icon', 50)->default('tag')->after('color');

            // Featured flag untuk menandai kategori unggulan
            $table->boolean('is_featured')->default(false)->after('icon');

            // Sort order untuk pengurutan kategori
            $table->integer('sort_order')->default(0)->after('is_featured');

            // Slug untuk SEO-friendly URLs (opsional)
            $table->string('slug')->nullable()->unique()->after('name');

            // Meta fields untuk SEO
            $table->string('meta_title')->nullable()->after('description');
            $table->text('meta_description')->nullable()->after('meta_title');

            // Active status untuk kategori
            $table->boolean('is_active')->default(true)->after('meta_description');

            // Index untuk performance
            $table->index(['is_featured', 'is_active']);
            $table->index('sort_order');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['is_featured', 'is_active']);
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['slug']);

            // Drop columns
            $table->dropColumn([
                'icon',
                'is_featured',
                'sort_order',
                'slug',
                'meta_title',
                'meta_description',
                'is_active'
            ]);
        });
    }
};
