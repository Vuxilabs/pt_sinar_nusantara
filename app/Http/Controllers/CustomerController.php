<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Support\Frontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return Frontend::render('customers.index', 'customers.index', [
            'customers' => Customer::query()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return Frontend::render('customers.create', 'customers.create', [

        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($request->validate([
            'name' => ['required', 'string'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]));

        return redirect()->route('customers.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Customer $customer): RedirectResponse
    {
        return redirect()->route('customers.edit', $customer);
    }

    public function edit(Customer $customer): View
    {
        return Frontend::render('customers.edit', 'customers.edit', [
            'customer' => $customer,

        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validate([
            'name' => ['required', 'string'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]));

        return redirect()->route('customers.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if (InventoryTransaction::query()->where('customer_id', $customer->id)->exists()) {
            return redirect()->route('customers.index')->with('error', 'Pelanggan sudah digunakan dalam riwayat transaksi.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Data berhasil dihapus.');
    }
}
