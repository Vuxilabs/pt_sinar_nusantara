@extends('layouts.dashboard')

@section('title', 'Pelanggan')

@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading">
        <div class="crud-heading-copy">
            <h1 class="crud-title">Pelanggan</h1>
            <p class="crud-description">Kelola Pelanggan.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="button button-primary">Tambah Pelanggan</a>
    </header>

    @if (session('success'))
        <p class="flash-success" role="status">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p class="flash-error" role="alert">{{ session('error') }}</p>
    @endif

    <div class="crud-summary">
        <span><strong class="crud-summary-count">{{ $customers->total() }}</strong> data</span>
        <label class="crud-search-wrap">
            <span class="sr-only">Cari data pada halaman ini</span>
            <input type="search" data-table-search placeholder="Cari…" class="crud-search">
        </label>
    </div>

    <div class="table-wrap">
        <table class="barang-table">
            <caption class="sr-only">Daftar Pelanggan</caption>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Nomor telepon</th>
                    <th>Alamat</th>
                    <th class="actions-heading">Aksi</th>
                </tr>
            </thead>
            <tbody data-table-body>
                @forelse ($customers as $customer)
                    <tr data-table-row>
                    <td>{{ $customer->{'name'} }}</td>
                    <td>{{ $customer->{'phone'} }}</td>
                    <td>{{ $customer->{'address'} }}</td>
                        <td class="actions-cell">
                            <div class="table-actions">
                                <a href="{{ route('customers.edit', $customer) }}" class="button button-secondary button-small">Edit</a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" data-confirm="Yakin ingin menghapus data ini?">
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
                                <p>Belum ada data.</p>
                                <a href="{{ route('customers.create') }}" class="button button-primary">Tambah Pelanggan</a>
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

    <div class="crud-pagination">{{ $customers->links() }}</div>
</section>
@endsection
