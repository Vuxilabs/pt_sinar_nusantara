<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\InventoryTransaction;
use App\Models\Warehouse;
use App\Support\Frontend;
use App\Support\InventoryStock;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly InventoryStock $stock) {}

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'warehouse_id' => ['nullable', 'integer', 'exists:warehouses,id'],
            'barang_id' => ['nullable', 'integer', 'exists:barangs,id'],
        ]);

        $warehouses = Warehouse::query()
            ->when($filters['warehouse_id'] ?? null, fn ($query, $id) => $query->whereKey($id))
            ->orderBy('name')
            ->get();
        $barangs = Barang::query()
            ->when($filters['barang_id'] ?? null, fn ($query, $id) => $query->whereKey($id))
            ->orderBy('sku')
            ->get();
        $stockDate = $filters['to'] ?? now()->toDateString();
        $balances = $this->stock->balances($stockDate)->keyBy(fn ($row) => $row->warehouse_id.':'.$row->barang_id);
        $stockRows = new Collection;

        foreach ($warehouses as $warehouse) {
            foreach ($barangs as $barang) {
                $key = $warehouse->id.':'.$barang->id;
                $stockRows->push((object) [
                    'warehouse' => $warehouse->name,
                    'sku' => $barang->sku,
                    'nama' => $barang->nama,
                    'satuan' => $barang->satuan,
                    'quantity' => (float) ($balances->get($key)?->quantity ?? 0),
                ]);
            }
        }

        $applyFilters = function ($query, string $type) use ($filters) {
            $query->where('type', $type)
                ->when($filters['from'] ?? null, fn ($query, $date) => $query->whereDate('occurred_at', '>=', $date))
                ->when($filters['to'] ?? null, fn ($query, $date) => $query->whereDate('occurred_at', '<=', $date))
                ->when($filters['warehouse_id'] ?? null, fn ($query, $id) => $query->where('warehouse_id', $id))
                ->when($filters['barang_id'] ?? null, fn ($query, $id) => $query->whereHas('items', fn ($items) => $items->where('barang_id', $id)));

            return $query;
        };
        $itemsForSelectedBarang = fn ($query) => $query->when(
            $filters['barang_id'] ?? null,
            fn ($query, $id) => $query->where('barang_id', $id),
        );

        $receipts = $applyFilters(InventoryTransaction::query(), 'receipt')
            ->with(['warehouse', 'user', 'items' => $itemsForSelectedBarang, 'items.barang'])
            ->orderByDesc('occurred_at')
            ->paginate(15, ['*'], 'receipts_page')
            ->appends($filters);

        $sales = $applyFilters(InventoryTransaction::query(), 'sale')
            ->with(['warehouse', 'customer', 'user', 'items' => $itemsForSelectedBarang, 'items.barang'])
            ->orderByDesc('occurred_at')
            ->paginate(15, ['*'], 'sales_page')
            ->appends($filters);

        return Frontend::render('reports.index', 'reports.index', [
            'stockRows' => $stockRows,
            'receipts' => $receipts,
            'sales' => $sales,
            'warehouses' => Warehouse::query()->orderBy('name')->get(),
            'barangs' => Barang::query()->orderBy('sku')->get(),
            'filters' => $filters,
            'stockDate' => $stockDate,
        ]);
    }
}
