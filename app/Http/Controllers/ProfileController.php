<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    /**
     * Update profile data (name, email, avatar)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maks 2MB
        ]);

        // 2. Handle Upload Foto
        if ($request->hasFile('photo')) {
            
            // Hapus foto lama jika ada (dan bukan default)
            // Kita cek di folder 'public/photos'
            $oldPhotoPath = public_path('photos/' . $user->photo);
            if ($user->photo && File::exists($oldPhotoPath)) {
                File::delete($oldPhotoPath);
            }

            // Buat nama file unik: user_id_timestamp.ext
            $fileName = 'user_' . $user->id . '_' . time() . '.' . $request->photo->extension();
            
            // Pindahkan file ke folder 'public/photos'
            // public_path() mengarah ke folder 'public' project Anda
            $request->photo->move(public_path('photos'), $fileName);
            
            // Simpan nama file ke database
            $user->photo = $fileName;
        }

        // 3. Update data lainnya
        $user->name = $request->name;
        $user->email = $request->email;
        
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update password only
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
