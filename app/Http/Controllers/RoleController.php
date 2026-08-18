<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// use Spatie\Permission\Contracts\Role;


class RoleController extends Controller
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
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $roles = Role::paginate(10);
        } else {
            $roles = Role::whereIn('name', ['admin', 'user', 'petugas'])->paginate(10);
        }

        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::orderBy('name', 'ASC')->get();
        return view("roles.create", [
            'permissions' => $permission
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:roles|min:3",
        ]);

        if ($validator->passes()) {
            $roles = Role::create(['name' => $request->name]);

            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $roles->givePermissionTo($roles);
                }

                return redirect()->route('roles.index')->with('success', 'Roles Add Successfully');
            } else {
                return redirect()->route('roles.create')->withErrors($validator)->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $roles = Role::all();
        return view('display', compact('roles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::findOrFail($id);
        $hasPermissions = $roles->permissions->pluck('name');
        $permission = Permission::orderBy('name', 'ASC')->get();

        return view('roles.edit', [
            'permissions' => $permission,
            'hasPermissions' => $hasPermissions,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $roles = Role::findOrFail($id);

        // Validasi
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('roles.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Update nama role
        $roles->name = $request->name;
        $roles->save();

        // Sync permissions
        if (!empty($request->permission)) {
            $roles->syncPermissions($request->permission);
        } else {
            $roles->syncPermissions([]); // Kosongkan permission jika tidak ada
        }

        return redirect()->route('roles.index')->with('success', 'Roles updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $roles = Role::findOrFail($id);
        $roles->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }
}
