<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            รายละเอียดสินค้า
        </h2>
    </x-slot>

    @php
        // เตรียม gallery_images ให้ปลอดภัย
        $gallery = $product->gallery_images;
        if (is_string($gallery)) {
            $gallery = json_decode($gallery, true);
        }
        $gallery = is_array($gallery) ? $gallery : [];

        // เตรียม attributes ให้ปลอดภัย
        $attrs = $product->attributes;
        if (is_string($attrs)) {
            $attrs = json_decode($attrs, true);
        }
        $attrs = is_array($attrs) ? $attrs : [];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 flex justify-end gap-2">
                    <a href="{{ route('products.index') }}"
                       class="bg-gray-500 hover:bg-gray-500/80 text-white text-xs font-bold p-3 rounded">
                        กลับ
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- ข้อมูลพื้นฐาน --}}
                    <div>
                        <h3 class="font-bold mb-2">ข้อมูลพื้นฐาน</h3>
                        <p><strong>รหัสสินค้า:</strong> {{ $product->id }}</p>
                        <p><strong>รหัสสินค้า (SKU):</strong> {{ $product->sku }}</p>
                        <p><strong>บาร์โค้ด:</strong> {{ $product->barcode }}</p>
                        <p><strong>ชื่อสินค้า:</strong> {{ $product->name }}</p>
                        <p><strong>ลิงก์ (Slug):</strong> {{ $product->slug }}</p>
                        <p><strong>หมวดหมู่:</strong> {{ $product->category_name }}</p>
                        <p><strong>แบรนด์:</strong> {{ $product->brand_name }}</p>
                    </div>

                    {{-- ราคา --}}
                    <div>
                        <h3 class="font-bold mb-2">ข้อมูลราคา</h3>
                        <p><strong>ราคาขาย:</strong> {{ number_format($product->price, 2) }} บาท</p>
                        <p><strong>ราคาก่อนลด:</strong> {{ number_format($product->compare_at_price, 2) }} บาท</p>
                        <p><strong>ต้นทุน:</strong> {{ number_format($product->cost, 2) }} บาท</p>
                        <p><strong>อัตราภาษี:</strong> {{ $product->tax_rate }} %</p>
                        <p><strong>ส่วนลด:</strong> {{ $product->discount_type }} ({{ $product->discount_value }})</p>
                    </div>

                    {{-- สต๊อก --}}
                    <div>
                        <h3 class="font-bold mb-2">ข้อมูลสต๊อก</h3>
                        <p><strong>จำนวนในคลัง:</strong> {{ $product->stock_qty }}</p>
                        <p><strong>จำนวนที่จองแล้ว:</strong> {{ $product->reserved_qty }}</p>
                        <p><strong>จุดสั่งซื้อใหม่:</strong> {{ $product->reorder_point }}</p>
                        <p><strong>สถานะสต๊อก:</strong> {{ $product->stock_status_text ?? $product->stock_status }}</p>
                    </div>

                    {{-- ขนาด --}}
                    <div>
                        <h3 class="font-bold mb-2">ขนาดและน้ำหนัก</h3>
                        <p><strong>น้ำหนัก:</strong> {{ $product->weight_kg }} กิโลกรัม</p>
                        <p><strong>ความยาว:</strong> {{ $product->length_cm }} ซม.</p>
                        <p><strong>ความกว้าง:</strong> {{ $product->width_cm }} ซม.</p>
                        <p><strong>ความสูง:</strong> {{ $product->height_cm }} ซม.</p>
                        <p><strong>ประเภทการจัดส่ง:</strong> {{ $product->shipping_class }}</p>
                    </div>

                </div>

                {{-- คำอธิบาย --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">คำอธิบายสินค้า</h3>
                    <p><strong>คำอธิบายสั้น:</strong> {{ $product->short_description }}</p>
                    <p class="mt-2"><strong>รายละเอียดเพิ่มเติม:</strong></p>
                    <div class="bg-gray-100 p-3 rounded">
                        {{ $product->description }}
                    </div>
                </div>

                {{-- รูปภาพ --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">รูปภาพสินค้า</h3>

                    @if($product->cover_image_url)
                        <div class="mb-3">
                            <strong>รูปปก:</strong><br>
                            <img src="{{ $product->cover_image_url }}"
                                 class="w-40 rounded border mt-2">
                        </div>
                    @endif

                    @if(!empty($gallery))
                        <div>
                            <strong>รูปภาพเพิ่มเติม:</strong>
                            <div class="flex gap-3 mt-2 flex-wrap">
                                @foreach($gallery as $img)
                                    <img src="{{ $img }}" class="w-24 rounded border">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- SEO --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">ข้อมูล SEO</h3>
                    <p><strong>Meta Title:</strong> {{ $product->meta_title }}</p>
                    <p><strong>Meta Description:</strong> {{ $product->meta_description }}</p>
                    <p><strong>Meta Keywords:</strong> {{ $product->meta_keywords }}</p>
                </div>

                {{-- อื่น ๆ --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">ข้อมูลเพิ่มเติม</h3>
                    <p><strong>สินค้าแนะนำ:</strong> {{ $product->is_featured ? 'ใช่' : 'ไม่ใช่' }}</p>
                    <p><strong>สถานะสินค้า:</strong> {{ $product->status }}</p>
                    <p><strong>วันที่เผยแพร่:</strong> {{ $product->published_at }}</p>
                    <p><strong>หมายเหตุ:</strong> {{ $product->notes }}</p>

                    @if(!empty($attrs))
                        <div class="mt-3">
                            <strong>คุณสมบัติสินค้า:</strong>
                            <pre class="bg-gray-100 p-3 rounded text-sm">{{ json_encode($attrs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>