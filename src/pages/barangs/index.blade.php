@extends('layouts.dashboard')

@section('title', 'Barang')

@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading">
        <div>
            <h1 class="crud-title">Barang</h1>
            <p class="crud-description">Kelola data barang.</p>
        </div>
        <a href="{{ route('barangs.create') }}" class="button button-primary">Tambah barang</a>
    </header>

    @if (session('success'))
        <p class="flash-success" role="status">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p class="flash-error" role="alert">{{ session('error') }}</p>
    @endif

    <div class="crud-summary">
        <span><strong class="crud-summary-count">{{ $barangs->total() }}</strong> barang</span>
        <label class="crud-search-wrap">
            <span class="sr-only">Cari barang</span>
            <input type="search" data-table-search placeholder="Cari barang" class="crud-search">
        </label>
    </div>

    <div class="table-wrap">
        <table class="barang-table">
            <caption class="sr-only">Daftar barang</caption>
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama barang</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Harga pokok</th>
                    <th>Harga jual</th>
                    <th>Status</th>
                    <th class="actions-heading">Aksi</th>
                </tr>
            </thead>
            <tbody data-table-body>
                @forelse ($barangs as $barang)
                    <tr data-table-row>
                        <td>{{ $barang->sku }}</td>
                        <td>{{ $barang->nama }}</td>
                        <td>{{ $barang->category?->name ?? '—' }}</td>
                        <td>{{ $barang->satuan }}</td>
                        <td>Rp {{ number_format((float) $barang->harga_pokok, 2, ',', '.') }}</td>
                        <td>Rp {{ number_format((float) $barang->harga_jual, 2, ',', '.') }}</td>
                        <td>{{ $barang->aktif ? 'Aktif' : 'Tidak aktif' }}</td>
                        <td class="actions-cell">
                            <div class="table-actions">
                                <a href="{{ route('barangs.edit', $barang) }}" class="button button-secondary button-small">Edit</a>
                                <form method="POST" action="{{ route('barangs.destroy', $barang) }}" data-confirm="Hapus barang ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-secondary button-small">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty-state" colspan="8">
                            <div class="crud-empty-state">
                                <h2>Belum ada barang</h2>
                                <p>Tambahkan barang untuk mulai mengelola persediaan.</p>
                                <a href="{{ route('barangs.create') }}" class="button button-primary">Tambah barang</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr data-table-no-results hidden>
                    <td class="empty-state" colspan="8">Tidak ada barang yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="crud-pagination">{{ $barangs->links() }}</div>
</section>
@endsection
