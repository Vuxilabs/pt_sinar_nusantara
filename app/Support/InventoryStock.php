<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InventoryStock
{
    public function atWarehouse(int $barangId, int $warehouseId): float
    {
        $balance = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('lines.barang_id', $barangId)
            ->where('transactions.status', 'posted')
            ->selectRaw(
                'COALESCE(SUM(CASE
                    WHEN transactions.type = ? AND transactions.warehouse_id = ? THEN lines.quantity
                    WHEN transactions.type = ? AND transactions.destination_warehouse_id = ? THEN lines.quantity
                    WHEN transactions.type = ? AND transactions.warehouse_id = ? THEN -lines.quantity
                    WHEN transactions.type = ? AND transactions.warehouse_id = ? THEN -lines.quantity
                    ELSE 0 END), 0) AS balance',
                ['receipt', $warehouseId, 'transfer', $warehouseId, 'sale', $warehouseId, 'transfer', $warehouseId],
            )
            ->value('balance');

        return (float) $balance;
    }

    public function totalsByBarang(): Collection
    {
        $movements = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('transactions.status', 'posted')
            ->select('lines.barang_id')
            ->selectRaw(
                'SUM(CASE WHEN transactions.type = ? THEN lines.quantity WHEN transactions.type = ? THEN -lines.quantity ELSE 0 END) AS quantity',
                ['receipt', 'sale'],
            )
            ->groupBy('lines.barang_id');

        return DB::table('barangs')
            ->leftJoinSub($movements, 'stock_totals', 'stock_totals.barang_id', '=', 'barangs.id')
            ->select('barangs.id', 'barangs.sku', 'barangs.nama', 'barangs.satuan')
            ->selectRaw('COALESCE(stock_totals.quantity, 0) AS quantity')
            ->orderBy('quantity')
            ->orderBy('barangs.nama')
            ->get();
    }

    public function balances(?string $throughDate = null): Collection
    {
        $receipts = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('transactions.status', 'posted')
            ->where('transactions.type', 'receipt')
            ->when($throughDate !== null, fn ($query) => $query->whereDate('transactions.occurred_at', '<=', $throughDate))
            ->select('lines.barang_id', 'transactions.warehouse_id')
            ->selectRaw('lines.quantity AS quantity');

        $sales = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('transactions.status', 'posted')
            ->where('transactions.type', 'sale')
            ->when($throughDate !== null, fn ($query) => $query->whereDate('transactions.occurred_at', '<=', $throughDate))
            ->select('lines.barang_id', 'transactions.warehouse_id')
            ->selectRaw('-lines.quantity AS quantity');

        $transferOut = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('transactions.status', 'posted')
            ->where('transactions.type', 'transfer')
            ->when($throughDate !== null, fn ($query) => $query->whereDate('transactions.occurred_at', '<=', $throughDate))
            ->select('lines.barang_id', 'transactions.warehouse_id')
            ->selectRaw('-lines.quantity AS quantity');

        $transferIn = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('transactions.status', 'posted')
            ->where('transactions.type', 'transfer')
            ->when($throughDate !== null, fn ($query) => $query->whereDate('transactions.occurred_at', '<=', $throughDate))
            ->select('lines.barang_id')
            ->selectRaw('transactions.destination_warehouse_id AS warehouse_id')
            ->selectRaw('lines.quantity AS quantity');

        $movements = $receipts->unionAll($sales)->unionAll($transferOut)->unionAll($transferIn);

        return DB::query()
            ->fromSub($movements, 'stock_movements')
            ->select('warehouse_id', 'barang_id')
            ->selectRaw('SUM(quantity) AS quantity')
            ->groupBy('warehouse_id', 'barang_id')
            ->get();
    }
}
