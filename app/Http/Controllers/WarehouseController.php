<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\InventoryTransaction;
use App\Support\Frontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        return Frontend::render('warehouses.index', 'warehouses.index', [
            'warehouses' => Warehouse::query()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return Frontend::render('warehouses.create', 'warehouses.create', [

        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Warehouse::create($request->validate([
            'name' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return redirect()->route('warehouses.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Warehouse $warehouse): RedirectResponse
    {
        return redirect()->route('warehouses.edit', $warehouse);
    }

    public function edit(Warehouse $warehouse): View
    {
        return Frontend::render('warehouses.edit', 'warehouses.edit', [
            'warehouse' => $warehouse,

        ]);
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validate([
            'name' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]));

        return redirect()->route('warehouses.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        if (InventoryTransaction::query()->where('warehouse_id', $warehouse->id)->orWhere('destination_warehouse_id', $warehouse->id)->exists()) {
            return redirect()->route('warehouses.index')->with('error', 'Gudang sudah digunakan dalam riwayat transaksi.');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Data berhasil dihapus.');
    }
}
