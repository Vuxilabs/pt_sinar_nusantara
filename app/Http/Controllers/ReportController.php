<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\InventoryTransaction;
use App\Models\Warehouse;
use App\Support\Frontend;
use App\Support\InventoryStock;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(private readonly InventoryStock $stock) {}

    public function index(): View
    {
        $warehouses = Warehouse::query()->orderBy('name')->get();
        $barangs = Barang::query()->orderBy('sku')->get();
        $balances = $this->stock->balances()->keyBy(fn ($row) => $row->warehouse_id.':'.$row->barang_id);
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

        $receipts = InventoryTransaction::query()
            ->with(['warehouse', 'user', 'items.barang'])
            ->where('type', 'receipt')
            ->orderByDesc('occurred_at')
            ->paginate(15, ['*'], 'receipts_page');

        $sales = InventoryTransaction::query()
            ->with(['warehouse', 'customer', 'user', 'items.barang'])
            ->where('type', 'sale')
            ->orderByDesc('occurred_at')
            ->paginate(15, ['*'], 'sales_page');

        return Frontend::render('reports.index', 'reports.index', [
            'stockRows' => $stockRows,
            'receipts' => $receipts,
            'sales' => $sales,
        ]);
    }
}
