@extends('layouts.app')

@section('content')
<section class="crud-shell narrow-shell">
    <header class="crud-form-heading">
        <a href="{{ route('crud_generator_products.index') }}" class="back-link">&larr; Kembali ke daftar</a>
        <p class="eyebrow">Data baru</p>
        <h1 class="crud-title">Tambah CrudGeneratorProduct</h1>
        <p class="crud-description">Isi informasi CrudGeneratorProduct.</p>
    </header>

    <form method="POST" action="{{ route('crud_generator_products.store') }}" class="barang-form">
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
        Name
        <input type="text" name="name" value="{{ old('name') }}" required class="crud-input">
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Description
        <textarea name="description" rows="4" class="crud-input">{{ old('description') }}</textarea>
        @error('description')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Price
        <input type="number" name="price" value="{{ old('price') }}" required class="crud-input">
        @error('price')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Published At
        <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="crud-input">
        @error('published_at')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Crud Generator Category Id
        <select name="crud_generator_category_id" class="crud-input">
            <option value="">Pilih Crud Generator Category Id</option>
            @foreach($crudGeneratorCategories as $crudGeneratorCategory)
                <option value="{{ $crudGeneratorCategory->id }}" @selected(old('crud_generator_category_id') == $crudGeneratorCategory->id)>{{ $crudGeneratorCategory->name }}</option>
            @endforeach
        </select>
        @error('crud_generator_category_id')<span class="form-error">{{ $message }}</span>@enderror
    </label>
    <label class="crud-field">
        Unconstrained Category Id
        <input type="number" name="unconstrained_category_id" value="{{ old('unconstrained_category_id') }}" class="crud-input">
        @error('unconstrained_category_id')<span class="form-error">{{ $message }}</span>@enderror
    </label>
        </div>
        <div class="crud-form-actions">
            <a href="{{ route('crud_generator_products.index') }}" class="button button-secondary">Batal</a>
            <button type="submit" class="button button-primary">Simpan</button>
        </div>
    </form>
</section>
@endsection
