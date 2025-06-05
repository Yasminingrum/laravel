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
        Schema::table('products', function (Blueprint $table) {
            // SEO dan URL fields
            $table->string('slug')->nullable()->unique()->after('name');
            $table->text('short_description')->nullable()->after('description');

            // Pricing fields untuk sistem harga yang lebih kompleks
            $table->decimal('compare_price', 12, 2)->nullable()->after('price')->comment('Original price before discount');
            $table->decimal('cost_price', 12, 2)->nullable()->after('compare_price')->comment('Cost price for profit calculation');

            // Inventory management
            $table->integer('min_stock')->default(5)->after('stock')->comment('Minimum stock level for alerts');

            // Product physical attributes
            $table->decimal('weight', 8, 2)->nullable()->after('min_stock')->comment('Weight in KG');
            $table->string('dimensions', 50)->nullable()->after('weight')->comment('Dimensions (LxWxH)');

            // Product identification
            $table->string('sku', 100)->nullable()->unique()->after('dimensions')->comment('Stock Keeping Unit');
            $table->string('barcode', 50)->nullable()->unique()->after('sku')->comment('Product barcode');

            // Product status and features
            $table->boolean('is_featured')->default(false)->after('is_active')->comment('Featured product flag');
            $table->boolean('is_digital')->default(false)->after('is_featured')->comment('Digital product flag');
            $table->boolean('requires_shipping')->default(true)->after('is_digital')->comment('Physical shipping required');

            // SEO meta fields
            $table->string('meta_title')->nullable()->after('requires_shipping');
            $table->text('meta_description')->nullable()->after('meta_title');

            // Publishing
            $table->timestamp('published_at')->nullable()->after('meta_description');

            // Add indexes for better performance
            $table->index(['is_active', 'is_featured']);
            $table->index(['category_id', 'is_active']);
            $table->index('published_at');
            $table->index('slug');
            $table->index('sku');
            $table->index('barcode');

            // Composite indexes for common queries
            $table->index(['is_active', 'published_at']);
            $table->index(['category_id', 'is_active', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['is_active', 'is_featured']);
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['sku']);
            $table->dropIndex(['barcode']);
            $table->dropIndex(['is_active', 'published_at']);
            $table->dropIndex(['category_id', 'is_active', 'is_featured']);

            // Drop columns
            $table->dropColumn([
                'slug',
                'short_description',
                'compare_price',
                'cost_price',
                'min_stock',
                'weight',
                'dimensions',
                'sku',
                'barcode',
                'is_featured',
                'is_digital',
                'requires_shipping',
                'meta_title',
                'meta_description',
                'published_at'
            ]);
        });
    }
};
