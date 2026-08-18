<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('parent')
            ->orderBy('order')
            ->get();


        $parents = Menu::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('menus.index', compact('menus', 'parents'));
    }

    public function create()
    {
        $parents = Menu::whereNull('parent_id')->get();
        return view('menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
        ]);

        Menu::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'url' => $request->url,
            'position' => $request->position,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Menu $menu)
    {
        $parents = Menu::whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->get();

        return view('menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'position' => 'required',
        ]);

        $menu->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
            'url' => $request->url,
            'position' => $request->position,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('menus.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus');
    }
}
