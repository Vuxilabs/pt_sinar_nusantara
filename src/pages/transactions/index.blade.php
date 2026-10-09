@extends('layouts.dashboard')
@section('title', 'Transaksi')
@section('body-class', 'dashboard-page')
@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading"><div><h1 class="crud-title">Transaksi</h1><p class="crud-description">Riwayat barang masuk, penjualan, dan transfer gudang.</p></div></header>
    @if (session('success'))<div class="flash-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="flash-error">{{ session('error') }}</div>@endif
    <div class="overview-actions"><a class="button button-primary" href="{{ route('transactions.receipts.create') }}">Barang masuk</a><a class="button button-secondary" href="{{ route('transactions.sales.create') }}">Penjualan</a><a class="button button-secondary" href="{{ route('transactions.transfers.create') }}">Transfer gudang</a></div>
    <div class="table-wrap"><table class="barang-table"><thead><tr><th>Nomor</th><th>Tanggal</th><th>Jenis</th><th>Gudang</th><th>Status</th><th class="actions-heading">Aksi</th></tr></thead><tbody>
    @forelse ($transactions as $transaction)
        <tr><td>{{ $transaction->number }}</td><td>{{ $transaction->occurred_at->format('d/m/Y H:i') }}</td><td>{{ ['receipt'=>'Barang masuk','sale'=>'Penjualan','transfer'=>'Transfer'][$transaction->type] }}</td><td>{{ $transaction->warehouse?->name }}@if($transaction->destinationWarehouse) → {{ $transaction->destinationWarehouse->name }}@endif</td><td>{{ $transaction->status === 'posted' ? 'Tercatat' : 'Dibatalkan' }}</td><td class="actions-cell"><a class="button button-secondary button-small" href="{{ route('transactions.show', $transaction) }}">Detail</a></td></tr>
    @empty
        <tr><td colspan="6" class="empty-state">Belum ada transaksi.</td></tr>
    @endforelse
    </tbody></table></div>
    <div class="crud-pagination">{{ $transactions->links() }}</div>
</section>
@endsection
