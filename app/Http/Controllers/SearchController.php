<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use App\Models\Menu;
use App\Models\SubMenu;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->input('q');

        if (!$q) return response()->json([]);

        // 1. Search Articles by title
        $articles = Article::where('title', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($a) => [
                'type' => 'Article',
                'title' => $a->title,
                'url' => route('articles.show', $a->id)
            ]);

        // 2. Search Categories
        $categories = Category::where('name', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'type' => 'Category',
                'title' => $c->name,
                'url' => route('categories.show', $c->id)
            ]);

        // 3. Search Menu
        $menus = Menu::where('name', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($m) => [
                'type' => 'Menu',
                'title' => $m->name,
                'url' => $m->url
            ]);

        // 4. Search SubMenu
        $submenus = SubMenu::where('name', 'like', "%{$q}%")
            ->take(5)
            ->get()
            ->map(fn($s) => [
                'type' => 'SubMenu',
                'title' => $s->name,
                'url' => $s->url
            ]);

        // Gabungkan semua
        $results = $articles->merge($categories)->merge($menus)->merge($submenus);

        return response()->json($results);
    }
}
