<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


   public function updatePhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $user = $request->user();

    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');

        // Buat nama file unik
        $filename = Str::slug($user->id . '-' . time()) . '.' . $file->getClientOriginalExtension();

        // Pindahkan file ke folder public/images/PhotoProfile
        $file->move(public_path('images/PhotoProfile'), $filename);

        // Hapus foto lama jika ada
        if ($user->photo && file_exists(public_path('images/PhotoProfile/' . $user->photo))) {
            unlink(public_path('images/PhotoProfile/' . $user->photo));
        }

        // Simpan nama file ke kolom `photo`
        $user->photo = $filename;
        $user->save();
    }

    return back()->with('success', 'Foto profil berhasil diganti!');
}

}
