<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller

{

    public function __construct()
    {
        // $this->middleware(function ($request, $next) {
        //     if (!auth()->user()->hasAnyRole('Super Admin', 'Admin')) {
        //         session()->flash('akses_ditolak', true);
        //         return redirect()->route('dashboard');
        //     }
        //     return $next($request);
        // });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $permission = Permission::orderBy('created_at', 'DESC')->paginate(10);
        return view("permissions.index", [
            "permissions" => $permission
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("permissions.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:permissions|min:3",
        ]);
        if ($validator->passes()) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permissions Add Successfully');
        } else {
            return redirect()->route('permissions.create')->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::all();
        return view('display', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $permission = Permission::findOrFail($id);
        return view('permissions.edit', [
            'permissions' => $permission
        ]);
        //  $permission = Permission::findOrFail($id);
        // return view('permissions.edit', compact('permission'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "name" => 'required|min:3|unique:permissions,name,' . $id . ' ,id'
        ]);

        if ($validator->passes()) {

            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('permissions.index')->with('success', 'Permission update successfully');
        } else {
            return redirect()->route('permissions.edit', $id)->withErrors($validator)->withInput($validator);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $permission = Permission::findOrFail($id);
    //     $permission->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Permission deleted successfully.'
    //     ]);
    // }
    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete permission: ' . $e->getMessage()
            ], 500);
        }
    }
}
