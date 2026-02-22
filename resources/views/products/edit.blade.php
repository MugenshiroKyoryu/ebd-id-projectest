<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            แก้ไขสินค้า
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-4 flex items-center justify-between">
                    <div class="text-center font-bold">
                        <h2>แก้ไขสินค้า</h2>
                    </div>

                    <a class="font-bold rounded text-xs bg-green-500 hover:bg-green-500/80 text-white p-4"
                       href="{{ route('products.index') }}">
                        Back
                    </a>
                </div>

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
                    // tags: array -> csv
                    $tagsCsv = old('tags', is_array($product->tags) ? implode(',', $product->tags) : ($product->tags ?? ''));

                    // images: array -> csv
                    $imagesCsv = old('images', is_array($product->images) ? implode(',', $product->images) : ($product->images ?? ''));

                    $publishedValue = old('published_at', optional($product->published_at)->format('Y-m-d\TH:i'));
                    $status = old('status', $product->status ?? 'draft');
                @endphp

                <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- id (อ่านอย่างเดียว) --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>ID :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-gray-100 border border-gray-200 rounded"
                                   type="text" value="{{ $product->id }}" readonly>
                        </div>
                    </div>

                    {{-- sku --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>SKU :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="sku" type="text" value="{{ old('sku', $product->sku) }}">
                            @error('sku') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- name --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>ชื่อสินค้า :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="name" type="text" value="{{ old('name', $product->name) }}">
                            @error('name') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- category --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>หมวดหมู่ :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="category" type="text" value="{{ old('category', $product->category) }}">
                            @error('category') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- brand --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>แบรนด์ :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="brand" type="text" value="{{ old('brand', $product->brand) }}">
                            @error('brand') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- tags (csv) --}}
                    <div class="flex flex-row items-center gap-4">
                        <div class="basis-1/5"><strong>Tags (คั่นด้วย ,) :</strong></div>
                        <div class="basis-4/5">
                            <input class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="tags" type="text" value="{{ $tagsCsv }}">
                            @error('tags') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- summary --}}
                    <div class="flex flex-row items-start gap-4">
                        <div class="basis-1/5 pt-2"><strong>คำอธิบายสั้น :</strong></div>
                        <div class="basis-4/5">
                            <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                      name="summary" rows="2">{{ old('summary', $product->summary) }}</textarea>
                            @error('summary') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- description --}}
                    <div class="flex flex-row items-start gap-4">
                        <div class="basis-1/5 pt-2"><strong>รายละเอียด :</strong></div>
                        <div class="basis-4/5">
                            <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                      name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- ราคา --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">ราคา</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <strong>ราคาขาย :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="price" type="number" step="0.01" value="{{ old('price', $product->price) }}">
                            @error('price') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>ราคาเทียบ :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="compare_price" type="number" step="0.01"
                                   value="{{ old('compare_price', $product->compare_price) }}">
                            @error('compare_price') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <strong>ต้นทุน :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="cost" type="number" step="0.01" value="{{ old('cost', $product->cost) }}">
                            @error('cost') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- สต๊อก --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">สต๊อก</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>จำนวนในสต๊อก :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="stock_qty" type="number" value="{{ old('stock_qty', $product->stock_qty) }}">
                            @error('stock_qty') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- รูป / สื่อ --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">รูป / สื่อ</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>Cover Image :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="cover_image" type="text" value="{{ old('cover_image', $product->cover_image) }}">
                            @error('cover_image') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror

                            @if(!empty($product->cover_image))
                                <div class="mt-2">
                                    <a href="{{ $product->cover_image }}" target="_blank" class="text-blue-600 underline text-sm">
                                        ดูรูปปก
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <strong>Images (คั่นด้วย ,) :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                   name="images" type="text" value="{{ $imagesCsv }}">
                            @error('images') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                            <div class="mt-1 text-xs text-gray-500">* ใส่หลาย path/URL คั่นด้วยเครื่องหมายจุลภาค</div>
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
                                   name="published_at" type="datetime-local" value="{{ $publishedValue }}">
                            @error('published_at') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="inline-flex items-center gap-2">
                            <input class="rounded border-gray-300" name="is_featured" type="checkbox" value="1"
                                   @checked(old('is_featured', (int)$product->is_featured) == 1)>
                            <span><strong>ตั้งเป็นสินค้าแนะนำ (Featured)</strong></span>
                        </label>
                        @error('is_featured') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">อื่น ๆ</h3>
                    </div>

                    <div class="flex flex-row items-start gap-4">
                        <div class="basis-1/5 pt-2"><strong>Notes :</strong></div>
                        <div class="basis-4/5">
                            <textarea class="w-full py-2 px-3 text-gray-700 bg-blue-50 border border-amber-100 rounded"
                                      name="notes" rows="2">{{ old('notes', $product->notes) }}</textarea>
                            @error('notes') <div class="mt-1 text-red-600 text-sm">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- created_at / updated_at (อ่านอย่างเดียว) --}}
                    <div class="pt-2 border-t">
                        <h3 class="font-bold text-gray-800 mt-4">เวลาในระบบ</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <strong>Created At :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-gray-100 border border-gray-200 rounded"
                                   type="text" value="{{ optional($product->created_at)->format('d/m/Y H:i') }}" readonly>
                        </div>
                        <div>
                            <strong>Updated At :</strong>
                            <input class="mt-1 w-full py-2 px-3 text-gray-700 bg-gray-100 border border-gray-200 rounded"
                                   type="text" value="{{ optional($product->updated_at)->format('d/m/Y H:i') }}" readonly>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-4">
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