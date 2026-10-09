@extends('layouts.dashboard')

@section('title', 'Kategori')

@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading">
        <div>
            <h1 class="crud-title">Kategori</h1>
            <p class="crud-description">Kelola kategori barang.</p>
        </div>
        <a href="{{ route('categories.create') }}" class="button button-primary">Tambah kategori</a>
    </header>

    @if (session('success'))
        <p class="flash-success" role="status">{{ session('success') }}</p>
    @endif

    <div class="crud-summary">
        <span><strong class="crud-summary-count">{{ $categories->total() }}</strong> kategori</span>
        <label class="crud-search-wrap">
            <span class="sr-only">Cari kategori</span>
            <input type="search" data-table-search placeholder="Cari kategori" class="crud-search">
        </label>
    </div>

    <div class="table-wrap">
        <table class="barang-table">
            <caption class="sr-only">Daftar kategori</caption>
            <thead>
                <tr><th>Nama kategori</th><th class="actions-heading">Aksi</th></tr>
            </thead>
            <tbody data-table-body>
                @forelse ($categories as $category)
                    <tr data-table-row>
                        <td>{{ $category->name }}</td>
                        <td class="actions-cell">
                            <div class="table-actions">
                                <a href="{{ route('categories.edit', $category) }}" class="button button-secondary button-small">Edit</a>
                                <form method="POST" action="{{ route('categories.destroy', $category) }}" data-confirm="Hapus kategori ini? Barang pada kategori ini akan tetap tersimpan tanpa kategori.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-secondary button-small">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty-state" colspan="2">
                            <div class="crud-empty-state">
                                <h2>Belum ada kategori</h2>
                                <p>Tambahkan kategori agar barang lebih mudah dikelola.</p>
                                <a href="{{ route('categories.create') }}" class="button button-primary">Tambah kategori</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr data-table-no-results hidden>
                    <td class="empty-state" colspan="2">Tidak ada kategori yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="crud-pagination">{{ $categories->links() }}</div>
</section>
@endsection
