<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DinasController extends Controller

{
    /**
     * Menampilkan semua dinas kategori 'kota'
     */

    /**
     * Tampilkan daftar data dinas (index)
     */
    public function index(Request $request)
    {
        $query = Dinas::query();

        // Filter pencarian
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $dinass = $query->latest()->paginate(10);

        return view('dinas.index', compact('dinass'));
    }

    /**
     * Simpan data dinas baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:kota,provinsi',
            'struktur' => 'nullable|string',
            'ket' => 'nullable|string',
            'link' => 'nullable|url'
        ]);

        Dinas::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'kategori' => $request->kategori,
            'struktur' => $request->struktur,
            'ket' => $request->ket,
            'link' => $request->link,
        ]);

        return redirect()->route('dinas.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Update data dinas
     */
    public function update(Request $request, Dinas $dina)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:kota,provinsi',
            'struktur' => 'nullable|string',
            'ket' => 'nullable|string',
            'link' => 'nullable|url'
        ]);

        $dina->update([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'struktur' => $request->struktur,
            'ket' => $request->ket,
            'link' => $request->link,
        ]);

        return redirect()->route('dinas.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus data dinas
     */
    public function destroy(Dinas $dina)
    {
        $dina->delete();

        return redirect()->route('dinas.index')->with('success', 'Data berhasil dihapus.');
    }
}
