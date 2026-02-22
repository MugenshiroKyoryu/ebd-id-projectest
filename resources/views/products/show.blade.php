<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            รายละเอียดสินค้า
        </h2>
    </x-slot>

    @php
        // images อาจเป็น array (จาก casts) หรือ string json (กรณีข้อมูลเก่า) -> ทำให้ปลอดภัย
        $images = $product->images;
        if (is_string($images)) {
            $images = json_decode($images, true);
        }
        $images = is_array($images) ? $images : [];

        $imageCount = count($images);
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
                        <p><strong>ID:</strong> {{ $product->id }}</p>
                        <p><strong>SKU:</strong> <span class="font-mono">{{ $product->sku }}</span></p>
                        <p><strong>ชื่อสินค้า:</strong> {{ $product->name }}</p>
                        <p><strong>หมวดหมู่:</strong> {{ $product->category ?? '-' }}</p>
                        <p><strong>แบรนด์:</strong> {{ $product->brand ?? '-' }}</p>

                        <p class="mt-2">
                            <strong>Tags:</strong>
                            @php
                                $tags = $product->tags;
                                if (is_string($tags)) $tags = json_decode($tags, true);
                                $tags = is_array($tags) ? $tags : [];
                            @endphp

                            @if(count($tags))
                                <span class="text-sm">
                                    {{ implode(', ', $tags) }}
                                </span>
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    {{-- ราคา / สต๊อก / สถานะ --}}
                    <div>
                        <h3 class="font-bold mb-2">ราคา / สต๊อก / สถานะ</h3>
                        <p><strong>ราคาขาย:</strong> {{ number_format((float)($product->price ?? 0), 2) }} บาท</p>

                        <p>
                            <strong>ราคาเทียบ:</strong>
                            {{ $product->compare_price !== null ? number_format((float)$product->compare_price, 2).' บาท' : '-' }}
                        </p>

                        <p>
                            <strong>ต้นทุน:</strong>
                            {{ $product->cost !== null ? number_format((float)$product->cost, 2).' บาท' : '-' }}
                        </p>

                        <p><strong>สต๊อก:</strong> {{ (int)($product->stock_qty ?? 0) }}</p>

                        <p>
                            <strong>สถานะสินค้า:</strong>
                            {{ $product->status ?? '-' }}
                        </p>

                        <p>
                            <strong>สินค้าแนะนำ:</strong>
                            {{ $product->is_featured ? 'ใช่' : 'ไม่ใช่' }}
                        </p>

                        <p>
                            <strong>วันที่เผยแพร่:</strong>
                            {{ $product->published_at?->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                </div>

                {{-- Summary / Description --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">คำอธิบายสินค้า</h3>

                    <p>
                        <strong>สรุป:</strong>
                        {{ $product->summary ?? '-' }}
                    </p>

                    <p class="mt-2"><strong>รายละเอียด:</strong></p>
                    <div class="bg-gray-100 p-3 rounded whitespace-pre-line">
                        {{ $product->description ?? '-' }}
                    </div>
                </div>

                {{-- รูปภาพ (แสดงอย่างอื่นแทนรูป: นับจำนวน + ลิงก์) --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">รูปภาพสินค้า</h3>

                    <div class="space-y-2">
                        <p>
                            <strong>รูปปก (Cover):</strong>
                            @if($product->cover_image)
                                <a href="{{ $product->cover_image }}" target="_blank" class="text-blue-600 underline">
                                    เปิดดู
                                </a>
                                <span class="text-xs text-gray-500 ml-2">{{ $product->cover_image }}</span>
                            @else
                                -
                            @endif
                        </p>

                        <p>
                            <strong>รูปเพิ่มเติม:</strong>
                            @if($imageCount > 0)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                    {{ $imageCount }} รูป
                                </span>
                            @else
                                <span class="inline-block bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-1 rounded">
                                    ไม่มีรูป
                                </span>
                            @endif
                        </p>

                        @if($imageCount > 0)
                            <div class="mt-2">
                                <ul class="list-disc pl-5 text-sm space-y-1">
                                    @foreach($images as $i => $url)
                                        <li>
                                            <a href="{{ $url }}" target="_blank" class="text-blue-600 underline">
                                                รูปที่ {{ $i + 1 }}
                                            </a>
                                            <span class="text-xs text-gray-500 ml-2">{{ $url }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- อื่น ๆ --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">ข้อมูลเพิ่มเติม</h3>
                    <p><strong>หมายเหตุ:</strong> {{ $product->notes ?? '-' }}</p>
                </div>

                {{-- เวลาในระบบ --}}
                <div class="mt-6">
                    <h3 class="font-bold mb-2">เวลาในระบบ</h3>
                    <p><strong>Created At:</strong> {{ $product->created_at?->format('d/m/Y H:i') ?? '-' }}</p>
                    <p><strong>Updated At:</strong> {{ $product->updated_at?->format('d/m/Y H:i') ?? '-' }}</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>