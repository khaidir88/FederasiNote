<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Dinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::with(['dinas', 'author'])->latest()->paginate(10);
        $dinasList = Dinas::all();

        return view('agendas.index', compact('agendas', 'dinasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dinas_id' => 'required|exists:dinas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
            'video' => 'nullable|string',
        ]);

        $data = $request->except('foto');
        $data['author_id'] = Auth::id();

        // Upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/agendas');

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $file->move($path, $filename);
            $data['foto'] = 'images/agendas/' . $filename;
        }

        Agenda::create($data);

        return redirect()->route('agendas.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function update(Request $request, Agenda $agenda)
    {
        $request->validate([
            'dinas_id' => 'required|exists:dinas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'link' => 'nullable|url',
            'video' => 'nullable|string',
        ]);

        $data = $request->except('foto');

        // Update foto (hapus lama jika ada)
        if ($request->hasFile('foto')) {
            if ($agenda->foto && File::exists(public_path($agenda->foto))) {
                File::delete(public_path($agenda->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/agendas');

            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $file->move($path, $filename);
            $data['foto'] = 'images/agendas/' . $filename;
        }

        $agenda->update($data);

        return redirect()->route('agendas.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->foto && File::exists(public_path($agenda->foto))) {
            File::delete(public_path($agenda->foto));
        }

        $agenda->delete();

        return redirect()->route('agendas.index')->with('success', 'Agenda berhasil dihapus.');
    }
}
