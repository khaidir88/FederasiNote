<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Dinas;
use Illuminate\Http\Request;

class KementerianController extends Controller
{
    /**
     * Display a listing of all dinas with optional filters
     */
    public function index(Request $request)
    {
        $query = Dinas::withCount('agendas');

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

    /**
     * Menampilkan semua dinas kategori 'kota'
     */
    // public function kota()
    // {
    //     $dinass = Dinas::where('kategori', 'kota')
    //         ->withCount('agendas')
    //         ->orderBy('nama')
    //         ->paginate(10);

    //     return view('kementerian.kota', compact('dinass'));
    // }
    public function kota(Request $request)
    {
        $query = Dinas::where('kategori', 'kota')->withCount('agendas')->orderBy('nama');

        if ($request->search) {
            $dinass = $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('struktur', 'like', "%{$request->search}%")
                    ->orWhere('ket', 'like', "%{$request->search}%");
            })->get(); // TANPA PAGINATE
        } else {
            $dinass = $query->paginate(10);
        }

        return view('kementerian.kota', compact('dinass'));
    }

    /**
     * Menampilkan semua dinas kategori 'provinsi'
     */
    // public function provinsi()
    // {
    //     $dinass = Dinas::where('kategori', 'provinsi')
    //         ->withCount('agendas')
    //         ->orderBy('nama')
    //         ->paginate(10);

    //     return view('kementerian.provinsi', compact('dinass'));
    // }
    public function provinsi(Request $request)
    {
        $query = Dinas::where('kategori', 'provinsi')->withCount('agendas')->orderBy('nama');

        if ($request->search) {
            $dinass = $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('struktur', 'like', "%{$request->search}%")
                    ->orWhere('ket', 'like', "%{$request->search}%");
            })->get();
        } else {
            $dinass = $query->paginate(10);
        }

        return view('kementerian.provinsi', compact('dinass'));
    }


    /**
     * Menampilkan semua dinas kategori 'kementerian'
     */
    // public function kementerians()
    // {
    //     $dinass = Dinas::where('kategori', 'kementerian')
    //         ->withCount('agendas')
    //         ->orderBy('nama')
    //         ->paginate(10);

    //     return view('kementerian.kementerian', compact('dinass'));
    // }
    public function kementerians(Request $request)
    {
        $query = Dinas::where('kategori', 'kementerian')->withCount('agendas')->orderBy('nama');

        if ($request->search) {
            $dinass = $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('struktur', 'like', "%{$request->search}%")
                    ->orWhere('ket', 'like', "%{$request->search}%");
            })->get();
        } else {
            $dinass = $query->paginate(10);
        }

        return view('kementerian.kementerian', compact('dinass'));
    }


    public function showdinas($slug)
    {
        $dinas = Dinas::where('slug', $slug)->firstOrFail();

        // Paginasi agenda (10 per halaman)
        $agendas = $dinas->agendas()->latest()->paginate(10);

        // Dinas lainnya untuk sidebar
        $otherDinas = Dinas::where('slug', '!=', $slug)
            ->withCount('agendas')
            ->latest()
            ->take(5)
            ->get();

        // Kirim agendas ke view sebagai collection terpisah
        return view('kementerian.dinas', [
            'dinas' => $dinas,
            'agendas' => $agendas,
            'otherDinas' => $otherDinas
        ]);
    }


    /**
     * Batas Kode sebelumnya
     */
    public function details($slug)
    {
        $agenda = Agenda::with('dinas')->where('slug', $slug)->firstOrFail();

        // Agenda lain (kecuali yang sedang dibuka)
        $agendaLainnya = Agenda::where('id', '!=', $agenda->id)
            ->latest()
            ->take(5)
            ->get();

        // Dinas lain
        $dinasLainnya = \App\Models\Dinas::where('id', '!=', $agenda->dinas_id)
            ->withCount('agendas')
            ->take(5)
            ->get();

        return view('kementerian.agenda-details', compact(
            'agenda',
            'agendaLainnya',
            'dinasLainnya'
        ));
    }

    /**
     * Alternatif URL: menampilkan agenda berdasarkan slug dinas dan agenda
     */
    public function agendaByDinas($dinasSlug, $agendaSlug)
    {
        $dinas = Dinas::where('slug', $dinasSlug)->firstOrFail();

        $agenda = Agenda::where('slug', $agendaSlug)
            ->where('dinas_id', $dinas->id)
            ->with('dinas')
            ->firstOrFail();

        $relatedAgendas = Agenda::where('dinas_id', $dinas->id)
            ->where('id', '!=', $agenda->id)
            ->latest()
            ->take(3)
            ->get();

        return view('kementerian.agenda-detail', compact('agenda', 'relatedAgendas'));
    }

    /**
     * Mencari agenda dari semua dinas
     */
    public function searchAgenda(Request $request)
    {
        $query = Agenda::with('dinas');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('dinas_id')) {
            $query->where('dinas_id', $request->dinas_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $agendas = $query->latest()->paginate(12);

        $dinasList = Dinas::orderBy('nama')->get();

        return view('kementerian.search-agenda', compact('agendas', 'dinasList'));
    }

    /**
     * API endpoint untuk mendapatkan agenda berdasarkan dinas (untuk AJAX)
     */
    public function getAgendasByDinas($dinasId)
    {
        $agendas = Agenda::where('dinas_id', $dinasId)
            ->select('id', 'judul', 'slug', 'tanggal')
            ->latest()
            ->get();

        return response()->json($agendas);
    }

    /**
     * Menampilkan semua agenda terbaru dari semua dinas
     */
    public function allAgendas()
    {
        $agendas = Agenda::with('dinas')
            ->latest()
            ->paginate(12);

        return view('kementerian.all-agendas', compact('agendas'));
    }

    public function search(Request $request)
    {
        $keyword = $request->search;

        $dinass = Dinas::where('nama', 'like', "%$keyword%")
            ->orWhere('struktur', 'like', "%$keyword%")
            ->orWhere('ket', 'like', "%$keyword%")
            ->orderBy('nama', 'asc')
            ->get();

        return view('kementerian.dinas_rows', compact('dinass'))->render();
    }
}
