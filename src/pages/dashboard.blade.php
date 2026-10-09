@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('body-class', 'dashboard-page')

@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading"><div><h1 class="crud-title">Dashboard</h1><p class="crud-description">Ringkasan persediaan dan transaksi.</p></div></header>
    <div class="overview-grid">
        <article class="overview-card"><span>Total barang</span><strong>{{ number_format($totalBarangs) }}</strong></article>
        <article class="overview-card"><span>Total gudang</span><strong>{{ number_format($totalWarehouses) }}</strong></article>
        <article class="overview-card"><span>Total pelanggan</span><strong>{{ number_format($totalCustomers) }}</strong></article>
        <article class="overview-card"><span>Penjualan hari ini</span><strong>Rp {{ number_format($salesToday, 0, ',', '.') }}</strong></article>
    </div>
    <section class="overview-section">
        <header class="overview-section-heading"><h2>Stok terendah</h2>@if(auth()->user()->role === 'admin')<a class="back-link" href="{{ route('reports.index') }}">Lihat laporan</a>@endif</header>
        <div class="table-wrap"><table class="barang-table"><thead><tr><th>SKU</th><th>Nama barang</th><th>Stok</th><th>Satuan</th></tr></thead><tbody>
        @forelse ($lowestStocks as $item)
            <tr><td>{{ $item->sku }}</td><td>{{ $item->nama }}</td><td>{{ number_format((float) $item->quantity, 3, ',', '.') }}</td><td>{{ $item->satuan }}</td></tr>
        @empty
            <tr><td colspan="4" class="empty-state">Belum ada barang.</td></tr>
        @endforelse
        </tbody></table></div>
    </section>
</section>
@endsection
