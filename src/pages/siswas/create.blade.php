@extends('layouts.app')

@section('content')
<section class="crud-shell narrow-shell">
    <header class="crud-form-heading">
        <a href="{{ route('siswas.index') }}" class="back-link">&larr; Kembali ke daftar</a>
        <p class="eyebrow">Data baru</p>
        <h1 class="crud-title">Tambah Siswa</h1>
        <p class="crud-description">Isi informasi Siswa.</p>
    </header>

    <form method="POST" action="{{ route('siswas.store') }}" class="barang-form">
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
        Nama
        <input type="text" name="nama" value="{{ old('nama') }}" required class="crud-input">
        @error('nama')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Nis
        <input type="text" name="nis" value="{{ old('nis') }}" required class="crud-input">
        @error('nis')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Kelas Id
        <select name="kelas_id" required class="crud-input">
            <option value="">Pilih Kelas Id</option>
            @foreach($kelas as $kela)
                <option value="{{ $kela->id }}" @selected(old('kelas_id') == $kela->id)>{{ $kela->id }}</option>
            @endforeach
        </select>
        @error('kelas_id')<span class="form-error">{{ $message }}</span>@enderror
    </label>
        </div>
        <div class="crud-form-actions">
            <a href="{{ route('siswas.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan</button>
        </div>
    </form>
</section>
@endsection
