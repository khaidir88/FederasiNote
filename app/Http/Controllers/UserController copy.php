<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Traits\HasRoles;

class UserController extends Controller
{
    public function __construct()
    {
        // Middleware inline untuk membatasi akses
        $this->middleware(function ($request, $next) {
            // Jika belum login → redirect ke login
            if (!Auth::check()) {
                return redirect()->route('login');
            }

            // Jika bukan Admin atau Super Admin → redirect ke dashboard
            if (!Auth::user()->hasAnyRole(['Admin', 'Super Admin'])) {
                // Tambahkan pesan flash agar bisa tampil alert nanti
                session()->flash('akses_ditolak', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
                return redirect()->route('dashboard');
            }

            return $next($request);
        });
    }



    // Menampilkan daftar users
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::all(); // Pastikan ini ada

        return view('users.index', compact('users', 'roles'));
    }


    // Menampilkan form registrasi
    public function create()
    {
        $roles = Role::all();
        return view('users.register', compact('roles'));
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        try {
            $data = $request->validated();

            // Handle photo upload - path ke public/images/photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photoPath = 'images/photo/' . $photoName;

                // Pindahkan file ke public/images/photo
                $photo->move(public_path('images/photo'), $photoName);
                $data['photo'] = $photoPath;
            }

            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'photo' => $data['photo'] ?? null,
                'is_active' => $request->has('is_active'),
            ]);

            // Assign roles
            $roles = $request->roles ?? ['user'];
            $user->syncRoles($roles);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil didaftarkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        $user->load('roles');
        return view('users.show', compact('user'));
    }

    // Menampilkan form edit
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'roles' => 'required|array',
            'roles.*' => 'required|string|exists:roles,name',
            'is_active' => 'sometimes|boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->only(['name', 'email']);
            $data['is_active'] = $request->has('is_active');

            // Handle photo upload - path ke public/images/photo
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && file_exists(public_path($user->photo))) {
                    unlink(public_path($user->photo));
                }

                $photo = $request->file('photo');
                $photoName = time() . '_' . $photo->getClientOriginalName();
                $photoPath = 'images/photo/' . $photoName;

                // Pindahkan file ke public/images/photo
                $photo->move(public_path('images/photo'), $photoName);
                $data['photo'] = $photoPath;
            }

            // Update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            // Sync roles
            $user->syncRoles($request->roles);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Delete user
    public function destroy(User $user)
    {
        try {
            // Tidak boleh delete diri sendiri
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Tidak dapat menghapus akun sendiri!');
            }

            // Delete photo if exists
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
