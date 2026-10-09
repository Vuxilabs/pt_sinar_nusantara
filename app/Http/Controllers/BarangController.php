<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Support\Frontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(): View
    {
        return Frontend::render('barangs.index', 'barangs.index', [
            'barangs' => Barang::query()->with('category')->orderBy('nama')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return Frontend::render('barangs.create', 'barangs.create', [
            'categories' => DB::table('categories')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Barang::create($request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:barangs,sku'],
            'nama' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'satuan' => ['required', 'string', 'max:50'],
            'harga_pokok' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'aktif' => ['required', 'boolean'],
        ]));

        return redirect()->route('barangs.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Barang $barang): RedirectResponse
    {
        return redirect()->route('barangs.edit', $barang);
    }

    public function edit(Barang $barang): View
    {
        return Frontend::render('barangs.edit', 'barangs.edit', [
            'barang' => $barang,
            'categories' => DB::table('categories')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $barang->update($request->validate([
            'sku' => ['required', 'string', 'max:100', Rule::unique('barangs', 'sku')->ignore($barang->id)],
            'nama' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'satuan' => ['required', 'string', 'max:50'],
            'harga_pokok' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'aktif' => ['required', 'boolean'],
        ]));

        return redirect()->route('barangs.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Barang $barang): RedirectResponse
    {
        if (DB::table('inventory_transaction_items')->where('barang_id', $barang->id)->exists()) {
            return redirect()->route('barangs.index')
                ->with('error', 'Barang sudah memiliki riwayat transaksi dan tidak dapat dihapus.');
        }

        $barang->delete();

        return redirect()->route('barangs.index')->with('success', 'Data berhasil dihapus.');
    }
}
