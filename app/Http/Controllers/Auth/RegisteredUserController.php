<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */

    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([

            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nohp' => 'required|string|max:15',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();

            // Simpan file ke folder public/images/PhotoProfile
            $file->move(public_path('images/PhotoProfile'), $filename);

            // Simpan hanya nama file (bukan path lengkap)
            $photoPath = $filename;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nohp' => $request->nohp,
            'photo'   => $photoPath,
            'password' => Hash::make($request->password),
        ]);

        // Assign default role "User"
        $user->assignRole('User');

        event(new Registered($user));

        session()->flash('success', 'Registrasi berhasil, silakan login.');

        return redirect()->route('login');
    }


    public function checkUnique(Request $request)
    {

        if ($request->has('email')) {
            $exists = User::where('email', $request->email)->exists();
            return response()->json(['exists' => $exists]);
        }

        if ($request->has('nohp')) {
            $nohp = preg_replace('/[^0-9]/', '', $request->nohp); // hanya angka

            // Normalisasi: jika diawali 0, buang 0 di depan
            $normalized = ltrim($nohp, '0');

            // Cek dengan dan tanpa 0
            $exists = User::whereRaw("REPLACE(nohp, '0', '') = ?", [$normalized])
                ->orWhere('nohp', $nohp)
                ->exists();

            return response()->json(['exists' => $exists]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
