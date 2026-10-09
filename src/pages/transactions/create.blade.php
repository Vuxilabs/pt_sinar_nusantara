@extends('layouts.dashboard')
@section('title', $title)
@section('body-class', 'dashboard-page')
@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading"><div><h1 class="crud-title">{{ $title }}</h1><p class="crud-description">Catat transaksi persediaan.</p></div><a class="back-link" href="{{ route('transactions.index') }}">Kembali</a></header>
    @if ($errors->any())<div class="form-error-summary">Periksa kembali data transaksi.</div>@endif
    @php($oldItems = old('items', [['barang_id' => '', 'quantity' => '1', 'unit_price' => '']]))
    <form class="barang-form" method="POST" action="{{ route('transactions.'.$type.'s.store') }}" @if($type === 'transfer') data-transfer-form data-stock-url="{{ route('transactions.stock-availability') }}" @endif>
        @csrf
        <div class="barang-form-grid">
            <label class="crud-field">{{ $type === 'transfer' ? 'Gudang asal' : 'Gudang' }}<select class="crud-input" name="warehouse_id" @if($type === 'transfer') data-transfer-source @endif required><option value="">Pilih gudang</option>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>@endforeach</select>@error('warehouse_id')<small class="form-error">{{ $message }}</small>@enderror</label>
            @if($type === 'transfer')<label class="crud-field">Gudang tujuan<select class="crud-input" name="destination_warehouse_id" data-transfer-destination required><option value="">Pilih gudang</option>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}" @selected(old('destination_warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>@endforeach</select><small class="form-error" data-warehouse-error hidden>Gudang asal dan tujuan harus berbeda.</small>@error('destination_warehouse_id')<small class="form-error">{{ $message }}</small>@enderror</label>@endif
            @if($type === 'sale')<label class="crud-field">Pelanggan (opsional)<select class="crud-input" name="customer_id"><option value="">Pelanggan umum</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}</option>@endforeach</select></label>@endif
        </div>
        <div class="transaction-items-heading"><h2>Barang</h2><button class="button button-secondary button-small" type="button" data-add-item>Tambah barang</button></div>
        <div class="transaction-items" data-items>
            @foreach($oldItems as $index => $oldItem)
            <div class="transaction-item-row {{ $type === 'sale' ? 'transaction-sale-row' : '' }}" data-item-row>
                <label class="crud-field">Nama barang<select class="crud-input" name="items[{{ $index }}][barang_id]" @if($type === 'transfer') data-transfer-barang @endif required><option value="">Pilih barang</option>@foreach($barangs as $barang)<option value="{{ $barang->id }}" data-price="{{ $barang->harga_jual }}" @selected(($oldItem['barang_id'] ?? '') == $barang->id)>{{ $barang->sku }} — {{ $barang->nama }}</option>@endforeach</select>@if($type === 'transfer')<small class="stock-availability" data-stock-availability>Pilih gudang asal dan barang untuk melihat stok.</small>@endif</label>
                <label class="crud-field">Jumlah<input class="crud-input" type="number" name="items[{{ $index }}][quantity]" min="0.001" step="0.001" value="{{ $oldItem['quantity'] ?? 1 }}" @if($type === 'transfer') data-transfer-quantity @endif required></label>
                @if($type === 'sale')<label class="crud-field">Harga satuan<input class="crud-input" type="number" name="items[{{ $index }}][unit_price]" min="0" step="0.01" value="{{ $oldItem['unit_price'] ?? '' }}" required></label>@endif
                <button class="button button-secondary button-small" type="button" data-remove-item>Hapus</button>
                @foreach(['barang_id','quantity','unit_price'] as $field)@error("items.$index.$field")<small class="form-error">{{ $message }}</small>@enderror @endforeach
            </div>
            @endforeach
        </div>
        @error('items')<p class="form-error">{{ $message }}</p>@enderror
        <label class="crud-field transaction-notes">Catatan<textarea class="crud-input" name="notes" rows="3">{{ old('notes') }}</textarea></label>
        <div class="crud-form-actions"><a class="button button-secondary" href="{{ route('transactions.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan transaksi</button></div>
    </form>
    <template data-item-template><div class="transaction-item-row {{ $type === 'sale' ? 'transaction-sale-row' : '' }}" data-item-row>
        <label class="crud-field">Nama barang<select class="crud-input" data-name="barang_id" @if($type === 'transfer') data-transfer-barang @endif required><option value="">Pilih barang</option>@foreach($barangs as $barang)<option value="{{ $barang->id }}" data-price="{{ $barang->harga_jual }}">{{ $barang->sku }} — {{ $barang->nama }}</option>@endforeach</select>@if($type === 'transfer')<small class="stock-availability" data-stock-availability>Pilih gudang asal dan barang untuk melihat stok.</small>@endif</label>
        <label class="crud-field">Jumlah<input class="crud-input" data-name="quantity" type="number" min="0.001" step="0.001" value="1" @if($type === 'transfer') data-transfer-quantity @endif required></label>
        @if($type === 'sale')<label class="crud-field">Harga satuan<input class="crud-input" data-name="unit_price" type="number" min="0" step="0.01" required></label>@endif
        <button class="button button-secondary button-small" type="button" data-remove-item>Hapus</button>
    </div></template>
</section>
@endsection
