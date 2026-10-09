@extends('layouts.dashboard')
@section('title', 'Laporan')
@section('body-class', 'dashboard-page')
@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading"><div><h1 class="crud-title">Laporan</h1><p class="crud-description">Ringkasan stok dan transaksi tercatat.</p></div></header>
    <div class="report-filter-scroll"><form method="GET" action="{{ route('reports.index') }}" class="report-filters">
        <label class="crud-field">Dari tanggal<input class="crud-input" type="date" name="from" value="{{ $filters['from'] ?? '' }}">@error('from')<small class="form-error">{{ $message }}</small>@enderror</label>
        <label class="crud-field">Sampai tanggal<input class="crud-input" type="date" name="to" value="{{ $filters['to'] ?? '' }}">@error('to')<small class="form-error">{{ $message }}</small>@enderror</label>
        <label class="crud-field">Gudang<select class="crud-input" name="warehouse_id"><option value="">Semua gudang</option>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}" @selected(($filters['warehouse_id'] ?? '') == $warehouse->id)>{{ $warehouse->name }}</option>@endforeach</select>@error('warehouse_id')<small class="form-error">{{ $message }}</small>@enderror</label>
        <label class="crud-field">Barang<select class="crud-input" name="barang_id"><option value="">Semua barang</option>@foreach($barangs as $barang)<option value="{{ $barang->id }}" @selected(($filters['barang_id'] ?? '') == $barang->id)>{{ $barang->sku }} — {{ $barang->nama }}</option>@endforeach</select>@error('barang_id')<small class="form-error">{{ $message }}</small>@enderror</label>
        <div class="report-filter-actions"><button class="button button-primary" type="submit">Terapkan</button><a class="button button-secondary" href="{{ route('reports.index') }}">Atur ulang</a></div>
    </form></div>
    <p class="report-filter-note">Daftar transaksi mengikuti rentang tanggal. Stok menunjukkan saldo hingga {{ \Carbon\Carbon::parse($stockDate)->format('d/m/Y') }}.</p>
    <section class="overview-section"><header class="overview-section-heading"><h2>Stok per gudang</h2><span class="report-section-note">Saldo hingga {{ \Carbon\Carbon::parse($stockDate)->format('d/m/Y') }}</span></header><div class="table-wrap"><table class="barang-table"><thead><tr><th>Gudang</th><th>SKU</th><th>Barang</th><th>Stok</th></tr></thead><tbody>
    @forelse($stockRows as $row)<tr><td>{{ $row->warehouse }}</td><td>{{ $row->sku }}</td><td>{{ $row->nama }}</td><td>{{ rtrim(rtrim(number_format((float) $row->quantity, 3, ',', '.'), '0'), ',') }} {{ $row->satuan }}</td></tr>@empty<tr><td colspan="4" class="empty-state">Belum ada data stok.</td></tr>@endforelse
    </tbody></table></div></section>
    <section class="overview-section"><header class="overview-section-heading"><h2>Barang masuk</h2></header><div class="table-wrap"><table class="barang-table"><thead><tr><th>Nomor</th><th>Tanggal</th><th>Gudang</th><th>{{ ($filters['barang_id'] ?? null) ? 'Jumlah item terpilih' : 'Jumlah item' }}</th><th>Status</th></tr></thead><tbody>
    @forelse($receipts as $transaction)<tr><td><a class="back-link" href="{{ route('transactions.show',$transaction) }}">{{ $transaction->number }}</a></td><td>{{ $transaction->occurred_at->format('d/m/Y H:i') }}</td><td>{{ $transaction->warehouse?->name }}</td><td>{{ $transaction->items->count() }}</td><td>{{ $transaction->status === 'posted' ? 'Tercatat' : 'Dibatalkan' }}</td></tr>@empty<tr><td colspan="5" class="empty-state">Belum ada barang masuk.</td></tr>@endforelse
    </tbody></table></div><div class="crud-pagination">{{ $receipts->links() }}</div></section>
    <section class="overview-section"><header class="overview-section-heading"><h2>Penjualan</h2></header><div class="table-wrap"><table class="barang-table"><thead><tr><th>Nomor</th><th>Tanggal</th><th>Gudang</th><th>Pelanggan</th><th>{{ ($filters['barang_id'] ?? null) ? 'Total item terpilih' : 'Total' }}</th><th>Status</th></tr></thead><tbody>
    @forelse($sales as $transaction)<tr><td><a class="back-link" href="{{ route('transactions.show',$transaction) }}">{{ $transaction->number }}</a></td><td>{{ $transaction->occurred_at->format('d/m/Y H:i') }}</td><td>{{ $transaction->warehouse?->name }}</td><td>{{ $transaction->customer?->name ?? 'Umum' }}</td><td>Rp {{ number_format($transaction->items->sum(fn($item) => (float)$item->quantity * (float)$item->unit_price), 0, ',', '.') }}</td><td>{{ $transaction->status === 'posted' ? 'Tercatat' : 'Dibatalkan' }}</td></tr>@empty<tr><td colspan="6" class="empty-state">Belum ada penjualan.</td></tr>@endforelse
    </tbody></table></div><div class="crud-pagination">{{ $sales->links() }}</div></section>
</section>
@endsection
