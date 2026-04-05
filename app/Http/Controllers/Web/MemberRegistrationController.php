<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MemberRegistration;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberRegistrationController extends Controller
{
    public function index()
    {
        $members = MemberRegistration::latest()->get();
        return view('pages.members.index', compact('members'));
    }

    public function show(MemberRegistration $member)
    {
        return view('pages.members.show', compact('member'));
    }

    // Menampilkan halaman form edit
    public function edit(MemberRegistration $member)
    {
        // Pastikan hanya data yang belum di-approve yang bisa diedit (opsional)
        return view('pages.members.edit', compact('member'));
    }

    // Memproses perubahan data
    public function update(Request $request, MemberRegistration $member)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nik_ktp'      => 'required|string|max:20|unique:member_registrations,nik_ktp,' . $member->id,
            'nik_karyawan' => 'required|string|max:20|unique:member_registrations,nik_karyawan,' . $member->id,
            'department'   => 'required|string',
            'birth_place' => 'required|string|max:100',
            'birth_date'  => 'required|date_format:d/m/Y',
            'gender'      => 'required|in:male,female',
            'address'     => 'required|string',
            'phone'       => 'required|string|max:20',
            'religion'    => 'nullable|string',
            'education'   => 'nullable|string',
        ], [
            'name.required'        => 'Nama lengkap wajib diisi.',
            'nik_ktp.required'     => 'NIK KTP wajib diisi.',
            'nik_ktp.unique'       => 'NIK KTP ini sudah terdaftar di sistem.',
            'nik_karyawan.required' => 'NIK Karyawan wajib diisi.',
            'nik_karyawan.unique'   => 'NIK Karyawan ini sudah terdaftar di sistem.',
            'department.required'  => 'Bagian / Departemen wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required'  => 'Tanggal lahir wajib diisi.',
            'birth_date.date'      => 'Format tanggal lahir tidak valid.',
            'gender.required'      => 'Jenis kelamin wajib dipilih.',
            'address.required'     => 'Alamat wajib diisi.',
            'phone.required'       => 'Nomor telepon wajib diisi.',
        ]);

        // Update data menggunakan fill untuk keamanan
        $birthDate = $request->birth_date ? \Carbon\Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d') : null;

        $member->fill([
            'name'         => $request->name,
            'nik_ktp'      => $request->nik_ktp,
            'nik_karyawan' => $request->nik_karyawan,
            'department'   => $request->department,
            'birth_place' => $request->birth_place,
            'birth_date'  => $birthDate,
            'gender'      => $request->gender,
            'address'     => $request->address,
            'phone'       => $request->phone,
            'religion'    => $request->religion,
            'education'   => $request->education,
        ]);

        $member->save();

        return redirect()->route('members.index',)
            ->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    public function approve(MemberRegistration $member)
    {
        // 1. Buat User Baru (Official Account)
        User::create([
            'name'          => $member->name,
            'nik_ktp'       => $member->nik_ktp,
            'nik_karyawan'  => $member->nik_karyawan,
            'phone'         => $member->phone,
            'department'    => $member->department,
            'birth_place'   => $member->birth_place,
            'birth_date'    => $member->birth_date,
            'joint_date'    => now()->format('Y-m-d'),
            'address'       => $member->address,
            'gender'        => $member->gender,
            'religion'      => $member->religion,
            'education'     => $member->education,

            // AUTH DATA DEFAULT
            'username'      => $member->nik_ktp, // Biar gampang login pake NIK KTP
            'password'      => Hash::make('password123'), // Password default request kamu
            'pin'           => Hash::make('123456'),
            'password_hint' => 'password123',
            'pin_hint'      => '123456',
            'role'          => 'user',
        ]);

        // 2. Update status pendaftarannya jadi approved
        $member->update([
            'status' => 'approved'
        ]);

        return redirect()->route('members.index')->with('success', 'Member berhasil di-approve!');
    }

    public function previewPdf(MemberRegistration $member)
    {
        // 1. Load relasi jika ada
        $member->load('referrer');

        // 2. Ambil semua setting yang dibutuhkan dalam 1 query
        $keys = [
            Setting::EMAIL_ORGANISASI,
            Setting::DASAR_HUKUM,
            Setting::KUASA_TEKS,
        ];
        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        $emailOrganisasi = $settings[Setting::EMAIL_ORGANISASI] ?? 'sppion18@gmail.com';
        $dasarHukum      = json_decode($settings[Setting::DASAR_HUKUM] ?? '[]', true) ?? [];
        $kuasaTeks       = $settings[Setting::KUASA_TEKS] ?? '';

        // 3. Generate PDF dari view
        $pdf = Pdf::loadView('pdf.member_report', compact(
            'member', 'emailOrganisasi', 'dasarHukum', 'kuasaTeks'
        ));

        // 4. Atur ukuran kertas (A4 Portrait)
        $pdf->setPaper('a4', 'portrait');

        // 5. Stream ke browser
        return $pdf->stream('Laporan-Pendaftaran-' . $member->nik_ktp . '.pdf');
    }

    public function reject(MemberRegistration $member)
    {
        $member->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('members.index')->with('success', 'Pendaftaran member berhasil ditolak.');
    }

    public function destroy(MemberRegistration $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Data registrasi member berhasil dihapus.');
    }
}
