@extends('layouts.dashboard')

@section('title', 'Edit Pelanggan')

@section('dashboard-content')
<section class="crud-shell narrow-shell">
    <header class="crud-form-heading">
        <a href="{{ route('customers.index') }}" class="back-link">&larr; Kembali ke daftar</a>
        <h1 class="crud-title">Edit Pelanggan</h1>
    </header>

    <form method="POST" action="{{ route('customers.update', $customer) }}" class="barang-form">
        @csrf
        @method('PUT')
        @if ($errors->any())
            <div class="form-error-summary" role="alert">
                <strong>Periksa kembali isian berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="barang-form-grid">
    <label class="crud-field">
        Nama
        <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="crud-input">
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Nomor telepon
        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="crud-input">
        @error('phone')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Alamat
        <textarea name="address" rows="4" class="crud-input">{{ old('address', $customer->address) }}</textarea>
        @error('address')<span class="form-error">{{ $message }}</span>@enderror
    </label>
        </div>
        <div class="crud-form-actions">
            <a href="{{ route('customers.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection
