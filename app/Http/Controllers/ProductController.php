<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
     * แปลง string "a,b,c" -> array ["a","b","c"]
     * ถ้าว่าง -> []
     */
    private function csvToArray(?string $value): array
    {
        $value = trim((string) $value);
        if ($value === '') return [];

        $parts = array_map('trim', explode(',', $value));
        return array_values(array_filter($parts, fn ($v) => $v !== ''));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:64|unique:products,sku',
            'name' => 'required|string|max:255',

            'category' => 'nullable|string|max:120',
            'brand' => 'nullable|string|max:120',

            'summary' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',

            'stock_qty' => 'required|integer|min:0',

            'cover_image' => 'nullable|string|max:500',
            'images' => 'nullable|string', // csv

            'status' => 'nullable|in:draft,active,archived',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',

            'notes' => 'nullable|string|max:500',
            'tags' => 'nullable|string', // csv
        ]);

        $data = $request->only([
            'sku',
            'name',
            'category',
            'brand',
            'summary',
            'description',
            'price',
            'compare_price',
            'cost',
            'stock_qty',
            'cover_image',
            'status',
            'published_at',
            'notes',
        ]);

        // defaults
        $data['status'] = $data['status'] ?? 'draft';
        $data['is_featured'] = $request->boolean('is_featured');

        // ✅ ส่งเป็น array ให้ Model จัดการ (อย่า json_encode เอง)
        $data['tags'] = $this->csvToArray($request->input('tags'));
        $data['images'] = $this->csvToArray($request->input('images'));

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
            'sku' => 'required|string|max:64|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',

            'category' => 'nullable|string|max:120',
            'brand' => 'nullable|string|max:120',

            'summary' => 'nullable|string|max:500',
            'description' => 'nullable|string',

            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',

            'stock_qty' => 'required|integer|min:0',

            'cover_image' => 'nullable|string|max:500',
            'images' => 'nullable|string', // csv

            'status' => 'nullable|in:draft,active,archived',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',

            'notes' => 'nullable|string|max:500',
            'tags' => 'nullable|string', // csv
        ]);

        $data = $request->only([
            'sku',
            'name',
            'category',
            'brand',
            'summary',
            'description',
            'price',
            'compare_price',
            'cost',
            'stock_qty',
            'cover_image',
            'status',
            'published_at',
            'notes',
        ]);

        $data['status'] = $data['status'] ?? ($product->status ?? 'draft');
        $data['is_featured'] = $request->boolean('is_featured');

        // ✅ ส่งเป็น array ให้ Model จัดการ
        $data['tags'] = $this->csvToArray($request->input('tags'));
        $data['images'] = $this->csvToArray($request->input('images'));

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product has been updated successfully');
    }

    public function destroy(Product $product)
    {
        // Hard delete (ลบจริง)
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product has been deleted successfully');
    }
}