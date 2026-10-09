@extends('layouts.app')

@section('content')
<section class="crud-shell narrow-shell">
    <header class="crud-form-heading">
        <a href="{{ route('kelas.index') }}" class="back-link">&larr; Kembali ke daftar</a>
        <p class="eyebrow">Data baru</p>
        <h1 class="crud-title">Tambah Kela</h1>
        <p class="crud-description">Isi informasi Kela.</p>
    </header>

    <form method="POST" action="{{ route('kelas.store') }}" class="barang-form">
        @csrf
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
        Nama Kelas
        <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" required class="crud-input">
        @error('nama_kelas')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Deskripsi
        <textarea name="deskripsi" rows="4" class="crud-input">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')<span class="form-error">{{ $message }}</span>@enderror
    </label>
        </div>
        <div class="crud-form-actions">
            <a href="{{ route('kelas.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan</button>
        </div>
    </form>
</section>
@endsection
