<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT

            // ตัวตนสินค้า
            $table->string('sku', 64)->unique();
            $table->string('barcode', 64)->nullable();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->string('short_description', 500)->nullable();
            $table->longText('description')->nullable();

            // จัดกลุ่ม (แบบตารางเดียว)
            $table->string('category_name', 120)->nullable()->index();
            $table->string('brand_name', 120)->nullable()->index();
            $table->json('tags')->nullable();

            // ราคา/ภาษี/ส่วนลด
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->enum('discount_type', ['none', 'percent', 'fixed'])->default('none');
            $table->decimal('discount_value', 12, 2)->nullable();

            // สต๊อก/คลัง
            $table->integer('stock_qty')->default(0);
            $table->integer('reserved_qty')->default(0);
            $table->integer('reorder_point')->nullable();
            $table->enum('stock_status', ['in_stock', 'out_of_stock', 'preorder'])->default('in_stock');

            // ขนส่ง/มิติ
            $table->decimal('weight_kg', 10, 3)->nullable();
            $table->decimal('length_cm', 10, 2)->nullable();
            $table->decimal('width_cm', 10, 2)->nullable();
            $table->decimal('height_cm', 10, 2)->nullable();
            $table->string('shipping_class', 80)->nullable();

            // รูป/สื่อ
            $table->string('cover_image_url', 500)->nullable();
            $table->json('gallery_images')->nullable();

            // สถานะการขาย
            $table->enum('status', ['draft', 'active', 'archived'])->default('draft')->index();
            $table->boolean('is_featured')->default(false);
            $table->dateTime('published_at')->nullable();

            // SEO
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();

            // อื่น ๆ
            $table->string('notes', 500)->nullable();
            $table->json('attributes')->nullable();

            // timestamps + soft deletes
            $table->timestamps();     // created_at, updated_at
            $table->softDeletes();    // deleted_at

            // เพิ่ม index ที่ต้องการ (บางอันใส่ไว้แล้วด้วย ->index() ด้านบน)
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};