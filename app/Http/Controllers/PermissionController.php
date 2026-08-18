<?php
// app/Http/Controllers/PermissionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasAnyRole(['Admin', 'Super Admin'])) {
                session()->flash('akses_ditolak', true);
                return redirect()->route('dashboard');
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     // Pastikan menggunakan model Permission dari Spatie
    //     $permissions = Permission::withCount('roles')
    //         ->latest()
    //         ->paginate(10);

    //     // Get unique modules from permissions
    //     $modules = Permission::select('module')
    //         ->distinct()
    //         ->whereNotNull('module')
    //         ->orderBy('module')
    //         ->pluck('module');

    //     return view('permissions.index', compact('permissions', 'modules'));
    // }

    public function index()
    {
        $permissions = Permission::withCount('roles')
            ->latest()
            ->paginate(10);

        // Ambil module dari database
        $dbModules = Permission::select('module')
            ->distinct()
            ->whereNotNull('module')
            ->orderBy('module')
            ->pluck('module')
            ->toArray();

        // Tambahkan daftar module statis
        $defaultModules = [
            'Dashboard',
            'User Management',
            'Article Management',
            'Agenda Management',
            'Category Management',
            'Dinas Management',
            'Role Management',
            'Permission Management',
            'Comment Management',
            'System Settings',
            'API Management',
            'Media Management',
            'Notification Management',
        ];

        // Gabungkan dan hilangkan duplikat
        $modules = collect(array_unique(array_merge($defaultModules, $dbModules)));

        return view('permissions.index', compact('permissions', 'modules'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = [
            'Dashboard',
            'User Management',
            'Article Management',
            'Category Management',
            'Role Management',
            'Permission Management',
            'Comment Management',
            'System Settings',
            'API Management',
            'Media Management',
            'Notification Management',
        ];

        $guardNames = ['web', 'api'];

        return view('permissions.create', compact('modules', 'guardNames'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:255',
            'guard_name' => 'required|string|in:web,api',
        ]);

        Permission::create([
            'name' => $request->name,
            'description' => $request->description,
            'module' => $request->module,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        $allRoles = Role::all();

        return view('permissions.show', compact('permission', 'allRoles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);

        $modules = [
            'Dashboard',
            'User',
            'Agenda',
            'Article',
            'Category',
            'Role',
            'Permission',
            'Comment',
            'System Settings',
            'API',
            'Media',
            'Notification',
        ];

        $guardNames = ['web', 'api'];

        return view('permissions.edit', compact('permission', 'modules', 'guardNames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions')->ignore($permission->id),
            ],
            'description' => 'nullable|string|max:500',
            'module' => 'required|string|max:255',
            'guard_name' => 'required|string|in:web,api',
        ]);

        $permission->update([
            'name' => $request->name,
            'description' => $request->description,
            'module' => $request->module,
            'guard_name' => $request->guard_name,
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);

        // Cek apakah permission digunakan oleh role
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Tidak dapat menghapus permission yang sedang digunakan oleh role!');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dihapus!');
    }

    /**
     * Assign permission to role
     */
    public function assignToRole(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->givePermissionTo($permission);

        return back()->with('success', "Permission berhasil diberikan ke role {$role->name}!");
    }

    /**
     * Revoke permission from role
     */
    public function revokeFromRole($permissionId, $roleId)
    {
        $permission = Permission::findOrFail($permissionId);
        $role = Role::findOrFail($roleId);

        $role->revokePermissionTo($permission);

        return back()->with('success', "Permission berhasil dicabut dari role {$role->name}!");
    }
}
