<?php

namespace App\Http\Controllers;

use App\Models\Kela;
use App\Support\Frontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelaController extends Controller
{
    public function index(): View
    {
        return Frontend::render('kelas.index', 'kelas.index', [
            'kelas' => Kela::query()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return Frontend::render('kelas.create', 'kelas.create', [

        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Kela::create($request->validate([
            'nama_kelas' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
        ]));

        return redirect()->route('kelas.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Kela $kela): RedirectResponse
    {
        return redirect()->route('kelas.edit', $kela);
    }

    public function edit(Kela $kela): View
    {
        return Frontend::render('kelas.edit', 'kelas.edit', [
            'kela' => $kela,

        ]);
    }

    public function update(Request $request, Kela $kela): RedirectResponse
    {
        $kela->update($request->validate([
            'nama_kelas' => ['required', 'string'],
            'deskripsi' => ['nullable', 'string'],
        ]));

        return redirect()->route('kelas.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Kela $kela): RedirectResponse
    {
        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Data berhasil dihapus.');
    }
}