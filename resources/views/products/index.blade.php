<!doctype html>
<html lang="th" data-theme="light">
<head>
  <meta charset="utf-8">
  <title>Product List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" />
</head>

<body class="bg-base-200 p-6">

<!-- มุมขวาบน Profile (ชิดขวาสุดของจอ) -->
<div class="fixed top-4 right-4 z-50">
  @auth
    <div class="dropdown dropdown-end">
      <label tabindex="0" class="btn btn-ghost btn-sm bg-base-100 shadow">
        <div class="flex items-center gap-2">
          <div class="avatar placeholder">
            <div class="bg-neutral text-neutral-content rounded-full w-8">
              <span class="text-xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
              </span>
            </div>
          </div>
          <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
        </div>
      </label>

      <ul tabindex="0"
          class="menu menu-sm dropdown-content mt-3 z-[9999] p-2 shadow bg-base-100 rounded-box w-52">
        @if (Route::has('dashboard'))
          <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @endif

        @if (Route::has('profile.edit'))
          <li><a href="{{ route('profile.edit') }}">Profile</a></li>
        @endif

        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  @endauth
</div>

<div class="container mx-auto pt-16">

  <!-- Header -->
  <div class="flex justify-between mb-6">
    <h1 class="text-2xl font-bold">ระบบจัดการสินค้า</h1>
    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
      + เพิ่มสินค้า
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success mb-4">
      {{ session('success') }}
    </div>
  @endif



  <div class="card bg-base-100 shadow">
    <div class="card-body p-0">

     <div class="overflow-x-auto">
  <table class="table table-zebra table-xs">
    <thead>
      <tr>
        <th>ID</th>
        <th>จำนวนรูป</th>
        <th>SKU</th>
        <th>ชื่อสินค้า</th>
        <th>หมวดหมู่</th>
        <th>แบรนด์</th>
        <th class="text-right">ราคาขาย</th>
        <th class="text-right">ราคาเทียบ</th>
        <th class="text-right">ต้นทุน</th>
        <th class="text-center">สต๊อก</th>
        <th>สถานะ</th>
        <th>สินค้าแนะนำ</th>
        <th>วันที่สร้าง</th>
        <th>แก้ไขล่าสุด</th>
        <th>หมายเหตุ</th>
        
        <th>จัดการ</th>
      </tr>
    </thead>

    <tbody>
      @forelse($products as $item)
        <tr>
          <td>{{ $item->id }}</td>

          <td class="text-center">
  @php
  $raw = $item->images ?? null;

  if (is_array($raw)) {
    $arr = $raw;
  } elseif (is_string($raw) && $raw !== '') {
    $decoded = json_decode($raw, true);
    $arr = is_array($decoded) ? $decoded : array_map('trim', explode(',', $raw));
  } else {
    $arr = [];
  }

  $arr = array_values(array_filter($arr, fn($v) => $v !== ''));
  $imageCount = count($arr);
@endphp

@if($imageCount > 0)
  <span class="badge badge-info badge-xs">{{ $imageCount }} รูป</span>
@else
  <span class="badge badge-ghost badge-xs">ไม่มีรูป</span>
@endif
</td>

          <td class="font-mono">{{ $item->sku }}</td>
          <td class="font-semibold">{{ $item->name }}</td>
          <td>{{ $item->category ?? '-' }}</td>
          <td>{{ $item->brand ?? '-' }}</td>

          <td class="text-right">
            {{ number_format((float)($item->price ?? 0), 2) }}
          </td>

          <td class="text-right">
            {{ $item->compare_price
                ? number_format((float)$item->compare_price, 2)
                : '-' }}
          </td>

          <td class="text-right">
            {{ number_format((float)($item->cost ?? 0), 2) }}
          </td>

          <td class="text-center">
            {{ (int)($item->stock_qty ?? 0) }}
          </td>

          <td>
            @if($item->status === 'active')
              <span class="badge badge-success badge-xs">active</span>
            @elseif($item->status === 'draft')
              <span class="badge badge-warning badge-xs">draft</span>
            @else
              <span class="badge badge-ghost badge-xs">archived</span>
            @endif
          </td>

          <td>
            @if($item->is_featured)
              <span class="badge badge-primary badge-xs">yes</span>
            @else
              <span class="badge badge-ghost badge-xs">no</span>
            @endif
          </td>
          <td class="text-xs">
            {{ $item->created_at?->format('d/m/Y') ?? '-' }}
          </td>

          <td class="text-xs">
  {{ $item->updated_at?->format('d/m/Y H:i') ?? '-' }}
</td>

          <td class="text-xs">
            {{ $item->notes ?? '-' }}
          </td>

          

          <td>
            <div class="flex gap-1">
              <a href="{{ route('products.show', $item->id) }}"
                 class="btn btn-warning btn-xs">Detail</a>

              <a href="{{ route('products.edit', $item->id) }}"
                 class="btn btn-info btn-xs">Edit</a>

              <form action="{{ route('products.destroy', $item->id) }}"
                    method="POST"
                    onsubmit="return confirm('ยืนยันการลบ?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-error btn-xs">Delete</button>
              </form>
            </div>
          </td>

        </tr>
      @empty
        <tr>
          <td colspan="16" class="text-center py-6">
            ยังไม่มีสินค้าในระบบ
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

      <div class="p-4">
        {{ $products->links() }}
      </div>

    </div>
  </div>

</div>

</body>
</html>