@extends('layouts.app')

@section('content')
<section class="crud-shell">
    <header class="crud-heading">
        <div class="crud-heading-copy">
            <p class="eyebrow">Data</p>
            <h1 class="crud-title">siswas</h1>
            <p class="crud-description">Kelola data siswas.</p>
        </div>
        <a href="{{ route('siswas.create') }}" class="button button-primary">Tambah Siswa</a>
    </header>

    @if (session('success'))
        <p class="flash-success" role="status">{{ session('success') }}</p>
    @endif

    <div class="crud-summary">
        <span><strong class="crud-summary-count">{{ $siswas->total() }}</strong> data</span>
        <label class="crud-search-wrap">
            <span class="sr-only">Cari data pada halaman ini</span>
            <input type="search" data-table-search placeholder="Cari…" class="crud-search">
        </label>
    </div>

    <div class="table-wrap">
        <table class="barang-table">
            <caption class="sr-only">Daftar siswas</caption>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nis</th>
                    <th>Kelas Id</th>
                    <th class="actions-heading">Aksi</th>
                </tr>
            </thead>
            <tbody data-table-body>
                @forelse ($siswas as $siswa)
                    <tr data-table-row>
                    <td>{{ $siswa->{'nama'} }}</td>
                    <td>{{ $siswa->{'nis'} }}</td>
                    <td>{{ $siswa->{'kelas_id'} }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('siswas.edit', $siswa) }}" class="button button-secondary button-small">Edit</a>
                                <form method="POST" action="{{ route('siswas.destroy', $siswa) }}" data-confirm="Yakin ingin menghapus data ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-secondary button-small">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty-state" colspan="4">
                            <div class="crud-empty-state">
                                <h2>Belum ada data</h2>
                                <p>Tambahkan Siswa pertama.</p>
                                <a href="{{ route('siswas.create') }}" class="button button-primary">Tambah Siswa</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr data-table-no-results hidden>
                    <td class="empty-state" colspan="4">Tidak ada data yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="crud-pagination">{{ $siswas->links() }}</div>
</section>
@endsection
