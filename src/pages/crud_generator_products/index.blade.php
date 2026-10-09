@extends('layouts.app')

@section('content')
<section class="crud-shell">
    <header class="crud-heading">
        <div class="crud-heading-copy">
            <p class="eyebrow">Data</p>
            <h1 class="crud-title">crudGeneratorProducts</h1>
            <p class="crud-description">Kelola data crudGeneratorProducts.</p>
        </div>
        <a href="{{ route('crud_generator_products.create') }}" class="button button-primary">Tambah CrudGeneratorProduct</a>
    </header>

    @if (session('success'))
        <p class="flash-success" role="status">{{ session('success') }}</p>
    @endif

    <div class="crud-summary">
        <span><strong class="crud-summary-count">{{ $crudGeneratorProducts->total() }}</strong> data</span>
        <label class="crud-search-wrap">
            <span class="sr-only">Cari data pada halaman ini</span>
            <input type="search" data-table-search placeholder="Cari…" class="crud-search">
        </label>
    </div>

    <div class="table-wrap">
        <table class="barang-table">
            <caption class="sr-only">Daftar crudGeneratorProducts</caption>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Published At</th>
                    <th>Crud Generator Category Id</th>
                    <th>Unconstrained Category Id</th>
                    <th class="actions-heading">Aksi</th>
                </tr>
            </thead>
            <tbody data-table-body>
                @forelse ($crudGeneratorProducts as $crudGeneratorProduct)
                    <tr data-table-row>
                    <td>{{ $crudGeneratorProduct->{'name'} }}</td>
                    <td>{{ $crudGeneratorProduct->{'description'} }}</td>
                    <td>{{ $crudGeneratorProduct->{'price'} }}</td>
                    <td>{{ $crudGeneratorProduct->{'published_at'} }}</td>
                    <td>{{ $crudGeneratorProduct->{'crud_generator_category_id'} }}</td>
                    <td>{{ $crudGeneratorProduct->{'unconstrained_category_id'} }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('crud_generator_products.edit', $crudGeneratorProduct) }}" class="button button-secondary button-small">Edit</a>
                                <form method="POST" action="{{ route('crud_generator_products.destroy', $crudGeneratorProduct) }}" data-confirm="Yakin ingin menghapus data ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-secondary button-small">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="empty-state" colspan="7">
                            <div class="crud-empty-state">
                                <h2>Belum ada data</h2>
                                <p>Tambahkan CrudGeneratorProduct pertama.</p>
                                <a href="{{ route('crud_generator_products.create') }}" class="button button-primary">Tambah CrudGeneratorProduct</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                <tr data-table-no-results hidden>
                    <td class="empty-state" colspan="7">Tidak ada data yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="crud-pagination">{{ $crudGeneratorProducts->links() }}</div>
</section>
@endsection
