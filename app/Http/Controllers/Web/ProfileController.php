<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }


    public function update(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'image_path' => 'nullable|image|max:5120',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'image_path.max' => 'Image maksimal 5MB.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Update Image jika ada
        if ($request->hasFile('image_path')) {
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }
            $user->image_path = $request->file('image_path')->store('profile/images', 'public');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->password_hint = $request->password;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile admin berhasil diperbarui.');
    }
}
