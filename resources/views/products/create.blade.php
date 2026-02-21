<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            เพิ่มสินค้า
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-4 flex justify-end">
                    <a class="font-bold rounded text-xs bg-green-500 hover:bg-green-500/80 text-white p-4"
                       href="{{ route('products.index') }}">
                        Back
                    </a>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($message = Session::get('success'))
                    <div class="mb-4 bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">{{ $message }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @php
                    $discountType = old('discount_type', 'none');
                    $stockStatus  = old('stock_status', 'in_stock');
                    $status       = old('status', 'draft');

                    // csv/json helpers
                    $tagsCsv    = old('tags', '');
                    $galleryCsv = old('gallery_images', '');
                    $attrsJson  = old('attributes', '');
                @endphp

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- SKU --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>SKU :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="sku" type="text" placeholder="เช่น SKU-0001" value="{{ old('sku') }}">
                            @error('sku') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Barcode --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>Barcode :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="barcode" type="text" placeholder="เช่น 885xxxx" value="{{ old('barcode') }}">
                            @error('barcode') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>ชื่อสินค้า :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="name" type="text" placeholder="ชื่อสินค้า" value="{{ old('name') }}">
                            @error('name') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Slug (อ่านอย่างเดียว - ระบบสร้างให้ใน Controller) --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>Slug :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-gray-100 border border-gray-200 rounded"
                                   name="slug" type="text" value="{{ old('slug') }}" readonly>
                            <div class="mt-1 text-xs text-gray-500">* ระบบจะสร้างจากชื่อสินค้าอัตโนมัติ</div>
                            @error('slug') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- short_description --}}
                    <div class="flex flex-row items-start gap-4">
                        <div class="basis-1/5 pt-2"><strong>คำอธิบายสั้น :</strong></div>
                        <div class="basis-4/5">
                            <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                      name="short_description" rows="2"
                                      placeholder="สรุปสั้น ๆ">{{ old('short_description') }}</textarea>
                            @error('short_description') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- description --}}
                    <div class="flex flex-row items-start gap-4">
                        <div class="basis-1/5 pt-2"><strong>รายละเอียด :</strong></div>
                        <div class="basis-4/5">
                            <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                      name="description" rows="5"
                                      placeholder="รายละเอียดสินค้าแบบยาว">{{ old('description') }}</textarea>
                            @error('description') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- category_name --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>หมวดหมู่ :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="category_name" type="text" placeholder="เช่น โทรศัพท์มือถือ" value="{{ old('category_name') }}">
                            @error('category_name') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- brand_name --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>แบรนด์ :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="brand_name" type="text" placeholder="เช่น Apple" value="{{ old('brand_name') }}">
                            @error('brand_name') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- tags --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>Tags (คั่นด้วย ,) :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="tags" type="text" placeholder="เช่น 5G,ใหม่,โปรโมชัน" value="{{ $tagsCsv }}">
                            @error('tags') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- ราคา/ภาษี/ส่วนลด --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">ราคา / ภาษี / ส่วนลด</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>ราคาขาย :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="price" type="number" step="0.01" value="{{ old('price', 0) }}">
                            @error('price') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>ราคาก่อนลด :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="compare_at_price" type="number" step="0.01" value="{{ old('compare_at_price') }}">
                            @error('compare_at_price') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>ต้นทุน :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="cost" type="number" step="0.01" value="{{ old('cost') }}">
                            @error('cost') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>อัตราภาษี (%) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="tax_rate" type="number" step="0.01" value="{{ old('tax_rate') }}">
                            @error('tax_rate') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>ประเภทส่วนลด :</strong>
                            <select name="discount_type" class="mt-1 w-full py-2 px-3 bg-blue-50 border border-amber-100 rounded">
                                <option value="none" @selected($discountType === 'none')>ไม่ลด</option>
                                <option value="percent" @selected($discountType === 'percent')>เปอร์เซ็นต์ (%)</option>
                                <option value="fixed" @selected($discountType === 'fixed')>ลดเป็นจำนวนเงิน</option>
                            </select>
                            @error('discount_type') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>มูลค่าส่วนลด :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="discount_value" type="number" step="0.01" value="{{ old('discount_value') }}">
                            @error('discount_value') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- สต๊อก/คลัง --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">สต๊อก / คลัง</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>จำนวนในสต๊อก :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="stock_qty" type="number" value="{{ old('stock_qty', 0) }}">
                            @error('stock_qty') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>จองแล้ว :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="reserved_qty" type="number" value="{{ old('reserved_qty', 0) }}">
                            @error('reserved_qty') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>จุดเตือนสั่งซื้อ (reorder) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="reorder_point" type="number" value="{{ old('reorder_point') }}">
                            @error('reorder_point') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>สถานะสต๊อก :</strong>
                            <select name="stock_status" class="mt-1 w-full py-2 px-3 bg-blue-50 border border-amber-100 rounded">
                                <option value="in_stock" @selected($stockStatus === 'in_stock')>มีสินค้า</option>
                                <option value="out_of_stock" @selected($stockStatus === 'out_of_stock')>สินค้าหมด</option>
                                <option value="preorder" @selected($stockStatus === 'preorder')>พรีออเดอร์</option>
                            </select>
                            @error('stock_status') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- ขนส่ง/มิติ --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">ขนส่ง / มิติ</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <strong>น้ำหนัก (kg) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="weight_kg" type="number" step="0.001" value="{{ old('weight_kg') }}">
                            @error('weight_kg') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>ยาว (cm) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="length_cm" type="number" step="0.01" value="{{ old('length_cm') }}">
                            @error('length_cm') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>กว้าง (cm) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="width_cm" type="number" step="0.01" value="{{ old('width_cm') }}">
                            @error('width_cm') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>สูง (cm) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="height_cm" type="number" step="0.01" value="{{ old('height_cm') }}">
                            @error('height_cm') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <strong>Shipping Class :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="shipping_class" type="text" placeholder="เช่น standard / bulky" value="{{ old('shipping_class') }}">
                            @error('shipping_class') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- รูป/สื่อ --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">รูป / สื่อ</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>Cover Image URL :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="cover_image_url" type="text" placeholder="https://...jpg" value="{{ old('cover_image_url') }}">
                            @error('cover_image_url') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>Gallery Images (คั่นด้วย ,) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="gallery_images" type="text" placeholder="url1,url2,url3" value="{{ $galleryCsv }}">
                            @error('gallery_images') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- สถานะการขาย --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">สถานะการขาย</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>สถานะสินค้า :</strong>
                            <select name="status" class="mt-1 w-full py-2 px-3 bg-blue-50 border border-amber-100 rounded">
                                <option value="draft" @selected($status === 'draft')>Draft</option>
                                <option value="active" @selected($status === 'active')>Active</option>
                                <option value="archived" @selected($status === 'archived')>Archived</option>
                            </select>
                            @error('status') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>วันที่เผยแพร่ :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="published_at" type="datetime-local" value="{{ old('published_at') }}">
                            @error('published_at') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2">
                            <input class="rounded border-gray-300" name="is_featured" type="checkbox" value="1"
                                   @checked(old('is_featured') == 1)>
                            <span><strong>ตั้งเป็นสินค้าแนะนำ (Featured)</strong></span>
                        </label>
                        @error('is_featured') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- SEO --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">SEO</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-row items-center gap-4">
                            <div class="basis-1/5"><strong>Meta Title :</strong></div>
                            <div class="basis-4/5">
                                <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                       name="meta_title" type="text" value="{{ old('meta_title') }}">
                                @error('meta_title') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="flex flex-row items-start gap-4">
                            <div class="basis-1/5 pt-2"><strong>Meta Description :</strong></div>
                            <div class="basis-4/5">
                                <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                          name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                                @error('meta_description') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="flex flex-row items-start gap-4">
                            <div class="basis-1/5 pt-2"><strong>Meta Keywords :</strong></div>
                            <div class="basis-4/5">
                                <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                          name="meta_keywords" rows="2">{{ old('meta_keywords') }}</textarea>
                                @error('meta_keywords') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- อื่น ๆ --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">อื่น ๆ</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-row items-start gap-4">
                            <div class="basis-1/5 pt-2"><strong>Notes :</strong></div>
                            <div class="basis-4/5">
                                <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                          name="notes" rows="2">{{ old('notes') }}</textarea>
                                @error('notes') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="flex flex-row items-start gap-4">
                            <div class="basis-1/5 pt-2">
                                <strong>Attributes (JSON) :</strong>
                                <div class="text-xs text-gray-500 mt-1">ตัวอย่าง: {"color":"Black","size":"M"}</div>
                            </div>
                            <div class="basis-4/5">
                                <textarea class="w-full py-2 px-3 font-mono text-sm text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                          name="attributes" rows="6">{{ $attrsJson }}</textarea>
                                @error('attributes') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                                class="font-bold rounded text-xs bg-blue-500 hover:bg-blue-500/80 text-white p-4">
                            Submit
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>