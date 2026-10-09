@extends('layouts.app')

@section('content')
<section class="crud-shell">
    <header class="crud-heading">
        <div class="crud-heading-copy">
            <p class="eyebrow">Data</p>
            <h1 class="crud-title">kelas</h1>
            <p class="crud-description">Kelola data kelas.</p>
        </div>
        <a href="{{ route('kelas.create') }}" class="button button-primary">Tambah Kela</a>
    </header>

    @if (session('success'))
        <p class="flash-success" role="status">{{ session('success') }}</p>
    @endif

    <div class="crud-summary">
        <span><strong class="crud-summary-count">{{ $kelas->total() }}</strong> data</span>
        <label class="crud-search-wrap">
            <span class="sr-only">Cari data pada halaman ini</span>
            <input type="search" data-table-search placeholder="Cari…" class="crud-search">
        </label>
    </div>

    <div class="table-wrap">
        <table class="barang-table">
            <caption class="sr-only">Daftar kelas</caption>
            <thead>
                <tr>
                    <th>Nama Kelas</th>
                    <th>Deskripsi</th>
                    <th class="actions-heading">Aksi</th>
                </tr>
            </thead>
            <tbody data-table-body>
                @forelse ($kelas as $kela)
                    <tr data-table-row>
                    <td>{{ $kela->{'nama_kelas'} }}</td>
                    <td>{{ $kela->{'deskripsi'} }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('kelas.edit', $kela) }}" class="button button-secondary button-small">Edit</a>
                                <form method="POST" action="{{ route('kelas.destroy', $kela) }}" data-confirm="Yakin ingin menghapus data ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-secondary button-small">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty-state" colspan="3">
                            <div class="crud-empty-state">
                                <h2>Belum ada data</h2>
                                <p>Tambahkan Kela pertama.</p>
                                <a href="{{ route('kelas.create') }}" class="button button-primary">Tambah Kela</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr data-table-no-results hidden>
                    <td class="empty-state" colspan="3">Tidak ada data yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="crud-pagination">{{ $kelas->links() }}</div>
</section>
@endsection
