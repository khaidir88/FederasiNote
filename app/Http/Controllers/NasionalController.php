<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NasionalController extends Controller
{
    /**
     * Display a listing of the resource.
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

        $kementerians = $query->latest()->paginate(10);

        return view('kementerian.index', compact('kementerians'));
    }

    public function kota()
    {
        $dinass = Dinas::where('kategori', 'kota')
            ->orderBy('nama')
            ->paginate(10);

        return view('kementerian.kota', compact('dinass'));
    }

    /**
     * Menampilkan semua dinas kategori 'provinsi'
     */
    public function provinsi()
    {
        $dinass = Dinas::where('kategori', 'provinsi')
            ->orderBy('nama')
            ->paginate(10);

        return view('kementerian.provinsi', compact('dinass'));
    }

    /**
     * Menampilkan detail dinas berdasarkan slug
     */
    public function showdinas($slug)
    {
        $dinas = Dinas::where('slug', $slug)->firstOrFail();

        return view('kementerian.showdinas', compact('dinas'));
    }
}
