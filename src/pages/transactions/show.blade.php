@extends('layouts.dashboard')
@section('title', 'Detail transaksi')
@section('body-class', 'dashboard-page')
@section('dashboard-content')
<section class="crud-shell">
    <header class="crud-heading"><div><h1 class="crud-title">{{ $transaction->number }}</h1><p class="crud-description">{{ ['receipt'=>'Barang masuk','sale'=>'Penjualan','transfer'=>'Transfer gudang'][$transaction->type] }} · {{ $transaction->occurred_at->format('d/m/Y H:i') }}</p></div><a class="back-link" href="{{ route('transactions.index') }}">Kembali ke transaksi</a></header>
    @if(session('success'))<div class="flash-success">{{ session('success') }}</div>@endif
    @if($errors->has('transaction'))<div class="flash-error">{{ $errors->first('transaction') }}</div>@endif
    <div class="transaction-meta"><div><span>Status</span><strong>{{ $transaction->status === 'posted' ? 'Tercatat' : 'Dibatalkan' }}</strong></div><div><span>Gudang</span><strong>{{ $transaction->warehouse?->name }}@if($transaction->destinationWarehouse) → {{ $transaction->destinationWarehouse->name }}@endif</strong></div><div><span>Petugas</span><strong>{{ $transaction->user?->name ?? '—' }}</strong></div>@if($transaction->customer)<div><span>Pelanggan</span><strong>{{ $transaction->customer->name }}</strong></div>@endif</div>
    <div class="table-wrap"><table class="barang-table">
        <thead><tr><th>Barang</th><th>Jumlah</th><th>Harga satuan</th>
        <th>Subtotal</th></tr></thead><tbody>@foreach($transaction->items as $item)<tr>
            <td>{{ $item->barang->sku }} — {{ $item->barang->nama }}</td>
            <td>{{ number_format((float)$item->quantity, 3, ',', '.') }}
                 {{ $item->barang->satuan }}</td>
                 <td>Rp {{ number_format((float)$item->unit_price) }}</td><td>
                    Rp {{ number_format((float)$item->quantity * (float)$item->unit_price, 0, ',', '.') }}</td>
                </tr>@endforeach</tbody></table></div>
    @if($transaction->notes)<p class="transaction-note">{{ $transaction->notes }}</p>@endif
    @if($transaction->status === 'posted')<form class="transaction-cancel" method="POST" action="{{ route('transactions.cancel', $transaction) }}" data-confirm="Batalkan transaksi ini? Riwayat tetap disimpan.">@csrf<button class="button button-secondary" type="submit">Batalkan transaksi</button></form>@endif
</section>
@endsection
