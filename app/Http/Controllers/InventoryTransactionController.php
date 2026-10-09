<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Warehouse;
use App\Support\Frontend;
use App\Support\InventoryStock;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InventoryTransactionController extends Controller
{
    public function __construct(private readonly InventoryStock $stock) {}

    public function index(): View
    {
        $transactions = InventoryTransaction::query()
            ->with(['warehouse', 'destinationWarehouse', 'customer', 'user', 'items.barang'])
            ->orderByDesc('occurred_at')
            ->orderByDesc('id')
            ->paginate(20);

        return Frontend::render('transactions.index', 'transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function createReceipt(): View
    {
        return $this->createForm('receipt');
    }

    public function createSale(): View
    {
        return $this->createForm('sale');
    }

    public function createTransfer(): View
    {
        return $this->createForm('transfer');
    }

    public function stockAvailability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'barang_id' => ['required', 'integer', 'exists:barangs,id'],
        ]);

        $barang = Barang::query()->findOrFail($data['barang_id']);

        return response()->json([
            'quantity' => $this->stock->atWarehouse((int) $barang->id, (int) $data['warehouse_id']),
            'unit' => $barang->satuan,
        ]);
    }

    public function storeReceipt(Request $request): RedirectResponse
    {
        return $this->store($request, 'receipt');
    }

    public function storeSale(Request $request): RedirectResponse
    {
        return $this->store($request, 'sale');
    }

    public function storeTransfer(Request $request): RedirectResponse
    {
        return $this->store($request, 'transfer');
    }

    public function show(InventoryTransaction $transaction): View
    {
        $transaction->load(['warehouse', 'destinationWarehouse', 'customer', 'user', 'items.barang']);

        return Frontend::render('transactions.show', 'transactions.show', [
            'transaction' => $transaction,
        ]);
    }

    public function cancel(InventoryTransaction $transaction): RedirectResponse
    {
        return DB::transaction(function () use ($transaction): RedirectResponse {
            $transaction = InventoryTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->id);

            if ($transaction->status !== 'posted') {
                return back()->with('error', 'Transaksi ini sudah dibatalkan.');
            }

            $items = $transaction->items()->orderBy('barang_id')->get();
            Barang::query()
                ->whereIn('id', $items->pluck('barang_id')->unique())
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($transaction->type === 'receipt') {
                $this->ensureCanRemoveStock($items, (int) $transaction->warehouse_id);
            }

            if ($transaction->type === 'transfer') {
                $this->ensureCanRemoveStock($items, (int) $transaction->destination_warehouse_id);
            }

            $transaction->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi dibatalkan. Riwayat tetap tersimpan.');
        });
    }

    private function createForm(string $type): View
    {
        $titles = [
            'receipt' => 'Barang masuk',
            'sale' => 'Penjualan',
            'transfer' => 'Transfer gudang',
        ];

        return Frontend::render('transactions.create', 'transactions.create', [
            'type' => $type,
            'title' => $titles[$type],
            'warehouses' => Warehouse::query()->where('is_active', true)->orderBy('name')->get(),
            'customers' => $type === 'sale' ? Customer::query()->orderBy('name')->get() : collect(),
            'barangs' => Barang::query()->where('aktif', true)->orderBy('sku')->get(),
        ]);
    }

    private function store(Request $request, string $type): RedirectResponse
    {
        $rules = [
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'array:barang_id,quantity,unit_price'],
            'items.*.barang_id' => ['required', 'integer', 'distinct', 'exists:barangs,id'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
        ];

        if ($type === 'sale') {
            $rules['customer_id'] = ['nullable', 'integer', 'exists:customers,id'];
            $rules['items.*.unit_price'] = ['required', 'numeric', 'min:0'];
        }

        if ($type === 'transfer') {
            $rules['destination_warehouse_id'] = ['required', 'integer', 'different:warehouse_id', 'exists:warehouses,id'];
        }

        $data = $request->validate($rules);

        return DB::transaction(function () use ($data, $type): RedirectResponse {
            $warehouse = Warehouse::query()->where('is_active', true)->find($data['warehouse_id']);
            if (! $warehouse) {
                throw ValidationException::withMessages(['warehouse_id' => 'Gudang tidak aktif atau tidak ditemukan.']);
            }

            $destination = null;
            if ($type === 'transfer') {
                $destination = Warehouse::query()->where('is_active', true)->find($data['destination_warehouse_id']);
                if (! $destination) {
                    throw ValidationException::withMessages(['destination_warehouse_id' => 'Gudang tujuan tidak aktif atau tidak ditemukan.']);
                }
            }

            $ids = collect($data['items'])->pluck('barang_id')->map(fn ($id) => (int) $id)->sort()->values();
            $barangs = Barang::query()
                ->whereIn('id', $ids)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($barangs->count() !== $ids->count() || $barangs->contains(fn (Barang $barang) => ! $barang->aktif)) {
                throw ValidationException::withMessages(['items' => 'Pilih barang yang masih aktif.']);
            }

            if (in_array($type, ['sale', 'transfer'], true)) {
                foreach ($data['items'] as $item) {
                    $available = $this->stock->atWarehouse((int) $item['barang_id'], (int) $warehouse->id);
                    if ($available + 0.0001 < (float) $item['quantity']) {
                        $barang = $barangs->get((int) $item['barang_id']);
                        throw ValidationException::withMessages([
                            'items' => "Stok {$barang->nama} tidak cukup di gudang {$warehouse->name}.",
                        ]);
                    }
                }
            }

            $transaction = InventoryTransaction::query()->create([
                'number' => 'TRX-'.now()->format('Ymd').'-'.Str::upper(Str::random(8)),
                'type' => $type,
                'warehouse_id' => $warehouse->id,
                'destination_warehouse_id' => $destination?->id,
                'customer_id' => $type === 'sale' ? ($data['customer_id'] ?? null) : null,
                'user_id' => auth()->id(),
                'status' => 'posted',
                'occurred_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $barang = $barangs->get((int) $item['barang_id']);
                $transaction->items()->create([
                    'barang_id' => $barang->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $type === 'sale'
                        ? $item['unit_price']
                        : $barang->harga_pokok,
                ]);
            }

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil disimpan.');
        });
    }

    private function ensureCanRemoveStock(EloquentCollection $items, int $warehouseId): void
    {
        foreach ($items->groupBy('barang_id') as $barangId => $lines) {
            $quantity = (float) $lines->sum('quantity');
            if ($this->stock->atWarehouse((int) $barangId, $warehouseId) + 0.0001 < $quantity) {
                $barang = Barang::query()->find($barangId);
                throw ValidationException::withMessages([
                    'transaction' => "Pembatalan akan membuat stok {$barang?->nama} menjadi negatif.",
                ]);
            }
        }
    }
}
