@extends('layouts.dashboard')

@section('title', 'Tambah kategori')

@section('dashboard-content')
<section class="crud-shell narrow-shell">
    <header class="crud-form-heading">
        <a href="{{ route('categories.index') }}" class="back-link">&larr; Kembali ke kategori</a>
        <h1 class="crud-title">Tambah kategori</h1>
    </header>

    <form method="POST" action="{{ route('categories.store') }}" class="barang-form">
        @csrf
        @if ($errors->any())
            <div class="form-error-summary" role="alert">
                <strong>Periksa kembali isian berikut.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <label class="crud-field">
            Nama kategori
            <input type="text" name="name" value="{{ old('name') }}" maxlength="255" required class="crud-input">
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
        </label>

        <div class="crud-form-actions">
            <a href="{{ route('categories.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan kategori</button>
        </div>
    </form>
</section>
@endsection
