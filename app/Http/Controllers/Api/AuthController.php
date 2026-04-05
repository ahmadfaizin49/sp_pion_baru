<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'kta_number' => 'required|string',
            'password' => 'required|string',
            'device_id' => 'required|string',
        ]);

        // 🔍 Cari user berdasarkan kta_number
        $user = User::where('kta_number', $request->kta_number)->first();

        // ❌ Cek User & Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Nomor KTA atau password salah',
            ], 401);
        }

        // ✅ Proteksi Role Admin (Admin hanya boleh lewat Web)
        if ($user->role === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Silahkan login melalui Dashboard Web'
            ], 403);
        }

        $deviceBinding = UserDevice::where('device_id', $request->device_id)->first();

        if ($deviceBinding) {
            if ($deviceBinding->user_id != $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Perangkat ini sudah terdaftar pada akun lain. Satu perangkat hanya dapat digunakan oleh satu akun.',
                ], 403);
            }

        }
        else {
            if ($user->device) {
                return response()->json([
                    'status' => false,
                    'message' => 'Akun Anda sudah terhubung di perangkat lain. Silakan hubungi admin untuk reset.'
                ], 403);
            }
            $user->device()->create([
                'device_id' => $request->device_id,
                'device_name' => $request->device_name ?? 'Unknown Device',
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
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

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'nik_ktp' => $user->nik_ktp,
            'nik_karyawan' => $user->nik_karyawan,
            'kta_number' => $user->kta_number,
            'barcode_number' => $user->barcode_number,
            'email' => $user->email,
            'department' => $user->department,
            'phone' => $user->phone,
            'birth_place' => $user->birth_place,
            'birth_date' => $user->birth_date,
            'address' => $user->address,
            'gender' => $user->gender,
            'religion' => $user->religion,
            'education' => $user->education,
            'role' => $user->role,
            'image_url' => $user->image_path
            ? asset('storage/' . $user->image_path)
            : null,
        ];

        return response()->json([
            'status' => true,
            'message' => 'Data profil berhasil diambil',
            'data' => $data
        ]);
    }

    public function downloadKta(Request $request)
    {
        $user = $request->user();

        // Generate KTA on-the-fly (tanpa menyimpan di storage)
        // 85.6mm x 110.0mm (2 Kartu atas-bawah ngepas tanpa celah bawah) -> 242.64pt x 311.81pt
        $pdf = Pdf::loadView('pdf.kta', compact('user'))
            ->setPaper([0, 0, 242.64, 311.81], 'portrait');

        $filename = 'KTA_' . str_replace(' ', '_', $user->name) . '_' . ($user->kta_number) . '.pdf';

        if ($request->query('mode') === 'view') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'nik_ktp' => 'sometimes|string|max:20|unique:users,nik_ktp,' . $user->id,
            'nik_karyawan' => 'sometimes|string|max:20|unique:users,nik_karyawan,' . $user->id,
            'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id,
            'department' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'birth_place' => 'sometimes|nullable|string|max:255',
            'birth_date' => 'sometimes|nullable|date',
            'address' => 'sometimes|nullable|string',
            'gender' => 'sometimes|nullable|in:male,female',
            'religion' => 'sometimes|nullable|string|max:100',
            'education' => 'sometimes|nullable|string|max:100',
            'image_path' => 'sometimes|nullable|file|mimes:jpg,jpeg,png|max:5120',
        ], [
            'image_path.max' => 'Ukuran foto maksimal adalah 5MB.',
            'image_path.mimes' => 'Format foto yang didukung hanya JPG, JPEG, dan PNG.',
            'image_path.file' => 'Foto yang diunggah harus berupa file yang valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $data = $request->only([
            'name',
            'nik_ktp',
            'nik_karyawan',
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
            'status' => true,
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
            'password' => Hash::make($request->new_password),
            'password_hint' => $request->new_password
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
            'pin' => Hash::make($request->new_pin),
            'pin_hint' => $request->new_pin
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
