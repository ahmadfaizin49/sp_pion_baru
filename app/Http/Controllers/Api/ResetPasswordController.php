<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpResetPassword;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function requestOtp(Request $request)
    {
        // Validasi email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => false,
                'message' => $errors->has('email') ? 'Email tidak terdaftar atau tidak aktif.' : $errors->first()
            ], 422);
        }

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        // Generate OTP 6 digit
        $otp = rand(100000, 999999);

        // Simpan OTP ke database dengan expiry 10 menit
        PasswordOtp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(10)]
        );

        // Kirim email dengan OTP
        Mail::to($request->email)->send(new OtpResetPassword($otp, $user->name));

        return response()->json([
            'status' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda.'
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $otpRecord = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'status' => false,
                'message' => 'OTP tidak valid atau kedaluwarsa.'
            ], 400);
        }

        // generate reset token
        $resetToken = Str::uuid()->toString();

        $otpRecord->update([
            'reset_token' => $resetToken,
            'used_at' => now()
        ]);

        return response()->json([
            'status' => true,
            'reset_token' => $resetToken
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'reset_token' => 'required|string',
                'password' => 'required|min:6|confirmed',
            ],
            [
                'password.confirmed' => 'Konfirmasi password tidak sesuai.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $otpRecord = PasswordOtp::where('reset_token', $request->reset_token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'status'  => false,
                'message' => 'Token reset tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        $user = User::where('email', $otpRecord->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $otpRecord->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Password berhasil direset. Silakan login dengan password baru Anda.'
        ], 200);
    }

}
