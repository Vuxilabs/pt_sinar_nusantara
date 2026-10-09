@extends('layouts.dashboard')

@section('title', 'Edit barang')

@section('dashboard-content')
<section class="crud-shell narrow-shell">
    <header class="crud-form-heading">
        <a href="{{ route('barangs.index') }}" class="back-link">&larr; Kembali ke barang</a>
        <h1 class="crud-title">Edit barang</h1>
    </header>

    <form method="POST" action="{{ route('barangs.update', $barang) }}" class="barang-form">
        @csrf
        @method('PUT')
        @if ($errors->any())
            <div class="form-error-summary" role="alert">
                <strong>Periksa kembali isian berikut.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="barang-form-grid">
            <label class="crud-field">
                SKU
                <input type="text" name="sku" value="{{ old('sku', $barang->sku) }}" maxlength="100" required class="crud-input">
                @error('sku')<span class="form-error">{{ $message }}</span>@enderror
            </label>
            <label class="crud-field">
                Nama barang
                <input type="text" name="nama" value="{{ old('nama', $barang->nama) }}" required class="crud-input">
                @error('nama')<span class="form-error">{{ $message }}</span>@enderror
            </label>
            <label class="crud-field">
                Kategori
                <select name="category_id" class="crud-input">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $barang->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<span class="form-error">{{ $message }}</span>@enderror
            </label>
            <label class="crud-field">
                Satuan
                <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" maxlength="50" required class="crud-input">
                @error('satuan')<span class="form-error">{{ $message }}</span>@enderror
            </label>
            <label class="crud-field">
                Harga pokok
                <input type="number" name="harga_pokok" value="{{ old('harga_pokok', $barang->harga_pokok) }}" min="0" step="0.01" required class="crud-input">
                @error('harga_pokok')<span class="form-error">{{ $message }}</span>@enderror
            </label>
            <label class="crud-field">
                Harga jual
                <input type="number" name="harga_jual" value="{{ old('harga_jual', $barang->harga_jual) }}" min="0" step="0.01" required class="crud-input">
                @error('harga_jual')<span class="form-error">{{ $message }}</span>@enderror
            </label>
            <label class="crud-field crud-checkbox-field">
                <input type="hidden" name="aktif" value="0">
                <input type="checkbox" name="aktif" value="1" class="crud-checkbox" @checked(old('aktif', $barang->aktif))>
                Barang aktif
            </label>
        </div>

        <div class="crud-form-actions">
            <a href="{{ route('barangs.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection
