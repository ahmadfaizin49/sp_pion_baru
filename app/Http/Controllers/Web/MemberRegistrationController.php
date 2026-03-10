<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MemberRegistration;
use App\Models\User;
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
            'name'        => 'required|string|max:255',
            'nik'         => 'required|string|max:20|unique:member_registrations,nik,' . $member->id,
            'department'  => 'required|string',
            'birth_place' => 'required|string|max:100',
            'birth_date'  => 'required|date',
            'gender'      => 'required|in:male,female',
            'address'     => 'required|string',
            'phone'       => 'required|string|max:20',
            'religion'    => 'nullable|string',
            'education'   => 'nullable|string',
        ], [
            'name.required'        => 'Nama lengkap wajib diisi.',
            'nik.required'         => 'NIK wajib diisi.',
            'nik.unique'           => 'NIK ini sudah terdaftar di sistem.',
            'department.required'  => 'Bagian / Departemen wajib diisi.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required'  => 'Tanggal lahir wajib diisi.',
            'birth_date.date'      => 'Format tanggal lahir tidak valid.',
            'gender.required'      => 'Jenis kelamin wajib dipilih.',
            'address.required'     => 'Alamat wajib diisi.',
            'phone.required'       => 'Nomor telepon wajib diisi.',
        ]);

        // Update data menggunakan fill untuk keamanan
        $member->fill([
            'name'        => $request->name,
            'nik'         => $request->nik,
            'department'  => $request->department,
            'birth_place' => $request->birth_place,
            'birth_date'  => $request->birth_date,
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
            'nik'           => $member->nik,
            'phone'         => $member->phone,
            'department'    => $member->department,
            'birth_place'   => $member->birth_place,
            'birth_date'    => $member->birth_date,
            'address'       => $member->address,
            'gender'        => $member->gender,
            'religion'      => $member->religion,
            'education'     => $member->education,

            // AUTH DATA DEFAULT
            'username'      => $member->nik, // Biar gampang login pake NIK
            'password'      => Hash::make('password1234'), // Password default request kamu
            'pin'           => Hash::make('000000'),
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
        // 1. Load relasi jika ada (misal ingin tahu siapa yang mendaftarkan)
        $member->load('referrer');

        // 2. Generate PDF dari view (kita akan buat view ini di langkah 2)
        $pdf = Pdf::loadView('pdf.member_report', compact('member'));

        // 3. Atur ukuran kertas (A4 Portrait)
        $pdf->setPaper('a4', 'portrait');

        // 4. Stream ke browser
        return $pdf->stream('Laporan-Pendaftaran-' . $member->nik . '.pdf');
    }
}
