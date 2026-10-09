<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Support\Frontend;
use App\Support\InventoryStock;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly InventoryStock $stock) {}

    public function index(): View
    {
        $salesToday = DB::table('inventory_transaction_items as lines')
            ->join('inventory_transactions as transactions', 'transactions.id', '=', 'lines.inventory_transaction_id')
            ->where('transactions.type', 'sale')
            ->where('transactions.status', 'posted')
            ->whereDate('transactions.occurred_at', today())
            ->sum(DB::raw('lines.quantity * lines.unit_price'));

        return Frontend::render('dashboard', 'dashboard', [
            'totalBarangs' => Barang::query()->count(),
            'totalWarehouses' => Warehouse::query()->count(),
            'totalCustomers' => Customer::query()->count(),
            'salesToday' => (float) $salesToday,
            'lowestStocks' => $this->stock->totalsByBarang()->take(5),
        ]);
    }
}
