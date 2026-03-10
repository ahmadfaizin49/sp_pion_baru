<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'username'   => 'required|string',
    //         'password'   => 'required|string',
    //     ]);

    //     $user = User::where('username', $request->username)->first();


    //     // ❌ Kalau user tidak ditemukan
    //     if (!$user) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Username atau password salah',
    //         ], 401);
    //     }

    //     // 🔐 Cek password
    //     if (!Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Username atau password salah',
    //         ], 401);
    //     }


    //     // ✅ Cegah login kalau role = admin
    //     if ($user->role === 'admin') {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Akun admin hanya bisa login melalui dashboard web'
    //         ], 403);
    //     }

    //     // ✅ Buat token login
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login berhasil',
    //         'data'    => [
    //             'user'  => $user,
    //             'token' => $token,
    //         ],
    //     ], 200);
    // }

    public function login(Request $request)
    {
        $request->validate([
            'kta_number' => 'required|string', // User input nomor KTA di sini
            'password'   => 'required|string',
        ]);

        // 🔍 Cari user SPESIFIK berdasarkan kta_number
        $user = User::where('kta_number', $request->kta_number)->first();

        // ❌ Kalau nomor KTA tidak ditemukan di Database
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Nomor KTA tidak terdaftar',
            ], 401);
        }

        // 🔐 Cek password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password yang Anda masukkan salah',
            ], 401);
        }

        // ✅ Proteksi Role (Admin tidak boleh login di Apps)
        if ($user->role === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Silahkan login melalui Dashboard Web'
            ], 403);
        }

        // ✅ Generate Token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data'    => [
                'user'  => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        // ✅ Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $user->image_url = $user->image_path
            ? asset('storage/' . $user->image_path)
            : null;
        unset($user->image_path);

        return response()->json([
            'status' => true,
            'message' => 'Data profil berhasil diambil',
            'data' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'        => 'sometimes|string|max:255',
            'email'       => 'sometimes|nullable|email|unique:users,email,' . $user->id,
            'department'  => 'sometimes|nullable|string|max:255',
            'phone'       => 'sometimes|nullable|string|max:20',
            'birth_place' => 'sometimes|nullable|string|max:255',
            'birth_date'  => 'sometimes|nullable|date',
            'address'     => 'sometimes|nullable|string',
            'gender'      => 'sometimes|nullable|in:male,female',
            'religion'    => 'sometimes|nullable|string|max:100',
            'education'   => 'sometimes|nullable|string|max:100',
            'image_path'  => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'name',
            'email',
            'department',
            'phone',
            'birth_place',
            'birth_date',
            'address',
            'gender',
            'religion',
            'education',
        ]);

        // 3. Logic Upload Foto
        if ($request->hasFile('image_path')) {

            // Ambil path asli dari DB sebelum dihapus (penting jika pakai Accessor)
            $oldImagePath = $user->getRawOriginal('image_path');

            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            // Simpan file baru
            $data['image_path'] = $request->file('image_path')->store('profiles', 'public');
        }

        $user->update($data);

        return response()->json([
            'status'  => true,
            'message' => 'Profil berhasil diperbarui'
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        // 🔐 Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password lama salah'
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil diperbarui'
        ]);
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'required|size:6',
            'new_pin' => 'required|size:6',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_pin, $user->pin)) {
            return response()->json([
                'status' => false,
                'message' => 'PIN lama salah'
            ], 401);
        }

        $user->update([
            'pin' => Hash::make($request->new_pin)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'PIN berhasil diperbarui'
        ]);
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6',
        ], [
            'pin.required' => 'PIN harus diisi',
            'pin.size' => 'PIN harus 6 digit',
        ]);

        $user = $request->user();

        // Cek apakah PIN yang diinput cocok dengan Hash di database
        if (!Hash::check($request->pin, $user->pin)) {
            return response()->json([
                'status' => false,
                'message' => 'PIN yang Anda masukkan salah',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Verifikasi berhasil',
        ], 200);
    }

    public function fcmToken(Request $request)
    {
        // ✅ Update FCM token user setelah login dari Aplikasi
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'FCM token berhasil diperbarui',
            'fcm_token' => $user->fcm_token,
        ]);
    }
}
