<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::orderBy('id', 'asc')->paginate(10);
    return view('products.index', compact('products'));
}

    public function create()
    {
        return view('products.create');
    }

    /**
     * สร้าง slug แบบไม่ซ้ำ (รองรับ update ด้วยการ ignore id เดิม)
     */
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;

        while (
            Product::when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $i; // iphone-15-128gb-2, -3, ...
            $i++;
        }

        return $slug;
    }

    /**
     * แปลง string "a,b,c" -> array ["a","b","c"]
     * ถ้าว่าง -> null
     */
    private function csvToArray(?string $value): ?array
    {
        $value = trim((string) $value);
        if ($value === '') return null;

        $parts = array_map('trim', explode(',', $value));
        $parts = array_values(array_filter($parts, fn ($v) => $v !== ''));
        return $parts ?: null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock_qty' => 'required|integer',
            'category_name' => 'required',
            'brand_name' => 'required',

            // เพิ่ม validate ที่เหลือแบบไม่บังคับ
            'slug' => 'nullable', // (อ่านอย่างเดียวในฟอร์ม)
            'barcode' => 'nullable|string|max:64',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'compare_at_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'tax_rate' => 'nullable|numeric',
            'discount_type' => 'nullable|in:none,percent,fixed',
            'discount_value' => 'nullable|numeric',
            'reserved_qty' => 'nullable|integer',
            'reorder_point' => 'nullable|integer',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,preorder',
            'weight_kg' => 'nullable|numeric',
            'length_cm' => 'nullable|numeric',
            'width_cm' => 'nullable|numeric',
            'height_cm' => 'nullable|numeric',
            'shipping_class' => 'nullable|string|max:80',
            'cover_image_url' => 'nullable|string|max:500',
            'gallery_images' => 'nullable|string', // รับเป็น csv
            'status' => 'nullable|in:draft,active,archived',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'tags' => 'nullable|string', // รับเป็น csv
            'attributes' => 'nullable', // รับเป็น JSON string ได้
        ]);

        $data = $request->all();

        // ✅ สร้าง slug แบบไม่ซ้ำ
        $data['slug'] = $this->uniqueSlug($request->name);

        // ✅ แปลง tags / gallery_images เป็น JSON array
        $tagsArr = $this->csvToArray($request->input('tags'));
        $galleryArr = $this->csvToArray($request->input('gallery_images'));

        $data['tags'] = $tagsArr ? json_encode($tagsArr, JSON_UNESCAPED_UNICODE) : null;
        $data['gallery_images'] = $galleryArr ? json_encode($galleryArr, JSON_UNESCAPED_UNICODE) : null;

        // ✅ attributes: ถ้าส่งมาเป็น string JSON ให้เก็บเป็น JSON (string) ตามแนวของท่าน
        // ถ้าอยากให้เก็บเป็น array ผ่าน $casts จะปรับได้
        if ($request->filled('attributes')) {
            $attrRaw = $request->input('attributes');

            // ถ้าผู้ใช้พิมพ์ JSON ใน textarea -> เก็บตามนั้น
            // ถ้า decode ได้จะ encode ใหม่ให้เป็นมาตรฐาน
            $decoded = json_decode($attrRaw, true);
            $data['attributes'] = is_array($decoded)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE)
                : null;
        } else {
            $data['attributes'] = null;
        }

        // defaults กัน null
        $data['discount_type'] = $data['discount_type'] ?? 'none';
        $data['reserved_qty'] = $data['reserved_qty'] ?? 0;
        $data['status'] = $data['status'] ?? 'draft';
        $data['stock_status'] = $data['stock_status'] ?? 'in_stock';
        $data['is_featured'] = (int) ($data['is_featured'] ?? 0);

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product has been added successfully');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'price' => 'required|numeric',
            'stock_qty' => 'required|integer',
            'category_name' => 'required',
            'brand_name' => 'required',

            // เพิ่ม validate ที่เหลือแบบไม่บังคับ
            'slug' => 'nullable',
            'barcode' => 'nullable|string|max:64',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'compare_at_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'tax_rate' => 'nullable|numeric',
            'discount_type' => 'nullable|in:none,percent,fixed',
            'discount_value' => 'nullable|numeric',
            'reserved_qty' => 'nullable|integer',
            'reorder_point' => 'nullable|integer',
            'stock_status' => 'nullable|in:in_stock,out_of_stock,preorder',
            'weight_kg' => 'nullable|numeric',
            'length_cm' => 'nullable|numeric',
            'width_cm' => 'nullable|numeric',
            'height_cm' => 'nullable|numeric',
            'shipping_class' => 'nullable|string|max:80',
            'cover_image_url' => 'nullable|string|max:500',
            'gallery_images' => 'nullable|string',
            'status' => 'nullable|in:draft,active,archived',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
            'tags' => 'nullable|string',
            'attributes' => 'nullable',
        ]);

        $data = $request->all();

        // ✅ update slug แบบไม่ซ้ำ (ignore id เดิม)
        $data['slug'] = $this->uniqueSlug($request->name, $product->id);

        // ✅ แปลง tags / gallery_images เป็น JSON
        $tagsArr = $this->csvToArray($request->input('tags'));
        $galleryArr = $this->csvToArray($request->input('gallery_images'));

        $data['tags'] = $tagsArr ? json_encode($tagsArr, JSON_UNESCAPED_UNICODE) : null;
        $data['gallery_images'] = $galleryArr ? json_encode($galleryArr, JSON_UNESCAPED_UNICODE) : null;

        // ✅ attributes JSON string
        if ($request->filled('attributes')) {
            $attrRaw = $request->input('attributes');
            $decoded = json_decode($attrRaw, true);
            $data['attributes'] = is_array($decoded)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE)
                : null;
        } else {
            $data['attributes'] = null;
        }

        $data['discount_type'] = $data['discount_type'] ?? 'none';
        $data['reserved_qty'] = $data['reserved_qty'] ?? 0;
        $data['status'] = $data['status'] ?? $product->status ?? 'draft';
        $data['stock_status'] = $data['stock_status'] ?? $product->stock_status ?? 'in_stock';
        $data['is_featured'] = (int) ($data['is_featured'] ?? 0);

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product has been updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product has been deleted successfully');
    }
}