<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Show profile page.
     */
    public function index()
    {
        return view('dashboard.profile', [
            'user' => Auth::user()
        ]);
    }

    /**
     * Update profile (name + photo)
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        /**
         * Upload photo
         */
        if ($request->hasFile('photo')) {

            // Buat folder jika belum ada
            $path = public_path('uploads/profile/');
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true);
            }

            // Hapus foto lama (jika ada)
            if ($user->photo && File::exists($path . $user->photo)) {
                File::delete($path . $user->photo);
            }

            // Upload foto baru
            $fileName = time() . '_' . uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move($path, $fileName);

            $user->photo = $fileName;
        }

        // Update nama
        $user->name = $request->name;
        $user->save();

        return back()->with('success', 'Profile updated!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
