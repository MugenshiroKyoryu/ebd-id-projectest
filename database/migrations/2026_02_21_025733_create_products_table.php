<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary Key

            // ข้อมูลหลักสินค้า
            $table->string('sku', 64)->unique();       // 1
            $table->string('name', 255)->index();      // 2

            // การจัดกลุ่ม
            $table->string('category', 120)->nullable()->index(); // 3
            $table->string('brand', 120)->nullable()->index();    // 4
            $table->json('tags')->nullable();                     // 5

            // เนื้อหา
            $table->string('summary', 500)->nullable();   // 6
            $table->longText('description')->nullable();  // 7

            // ราคา
            $table->decimal('price', 12, 2)->default(0);        // 8
            $table->decimal('compare_price', 12, 2)->nullable(); // 9
            $table->decimal('cost', 12, 2)->nullable();          // 10

            // สต๊อก
            $table->integer('stock_qty')->default(0); // 11

            // รูปภาพ
            $table->string('cover_image', 500)->nullable(); // 12
            $table->json('images')->nullable();              // 13

            // สถานะ
            $table->enum('status', ['draft', 'active', 'archived'])
                  ->default('draft')
                  ->index();                                 // 14

            $table->boolean('is_featured')->default(false);   // 15
            $table->dateTime('published_at')->nullable();     // 16

            // อื่น ๆ
            $table->string('notes', 500)->nullable();  // 17

            // เวลาระบบ
            $table->timestamps();
           
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};