<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Support\Frontend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(): View
    {
        return Frontend::render('siswas.index', 'siswas.index', [
            'siswas' => Siswa::query()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return Frontend::render('siswas.create', 'siswas.create', [
            'kelas' => DB::table('kelas')->orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Siswa::create($request->validate([
            'nama' => ['required', 'string'],
            'nis' => ['required', 'string'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ]));

        return redirect()->route('siswas.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): RedirectResponse
    {
        return redirect()->route('siswas.edit', $siswa);
    }

    public function edit(Siswa $siswa): View
    {
        return Frontend::render('siswas.edit', 'siswas.edit', [
            'siswa' => $siswa,
            'kelas' => DB::table('kelas')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $siswa->update($request->validate([
            'nama' => ['required', 'string'],
            'nis' => ['required', 'string'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
        ]));

        return redirect()->route('siswas.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect()->route('siswas.index')->with('success', 'Data berhasil dihapus.');
    }
}