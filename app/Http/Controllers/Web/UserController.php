<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VoteOption;
use App\Models\MemberRegistration;
use App\Models\Device;
use App\Models\TicketReply;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Exports\UsersExport;
use App\Exports\UserTemplateExport;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->latest()->get();
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik_ktp' => 'nullable|string|max:255|unique:users,nik_ktp',
            'nik_karyawan' => 'required|string|max:255|unique:users,nik_karyawan',
            'kta_number' => 'required|string|max:255|unique:users,kta_number',
            'barcode_number' => 'required|string|max:255|unique:users,barcode_number',
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date_format:d/m/Y',
            'joint_date' => 'nullable|date_format:d/m/Y',
            'gender' => 'nullable|in:male,female',
            'religion' => 'nullable|string',
            'education' => 'nullable|string',
            'address' => 'nullable|string',
            'pin' => 'required|string|size:6',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'nik_ktp.unique' => 'NIK KTP sudah digunakan.',
            'nik_karyawan.required' => 'NIK Karyawan wajib diisi.',
            'nik_karyawan.unique' => 'NIK Karyawan sudah digunakan.',
            'kta_number.required' => 'KTA wajib diisi.',
            'kta_number.unique' => 'Nomor KTA sudah digunakan oleh member lain.',
            'barcode_number.unique' => 'Nomor barcode sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'pin.size' => 'PIN harus 6 digit.',
        ]);

        $birthDate = $request->birth_date ?\Carbon\Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d') : null;
        $jointDate = $request->joint_date ?\Carbon\Carbon::createFromFormat('d/m/Y', $request->joint_date)->format('Y-m-d') : null;

        User::create([
            'name' => $request->name,
            'nik_ktp' => $request->nik_ktp,
            'nik_karyawan' => $request->nik_karyawan,
            'username' => $request->nik_ktp,
            'kta_number' => $request->kta_number,
            'barcode_number' => $request->barcode_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'birth_place' => $request->birth_place,
            'birth_date' => $birthDate,
            'joint_date' => $jointDate,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'education' => $request->education,
            'address' => $request->address,
            'role' => 'user',
            'pin' => Hash::make($request->pin),
            'password' => Hash::make($request->password),
            'pin_hint' => $request->pin,
            'password_hint' => $request->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        return view('pages.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik_ktp' => 'required|string|max:255|unique:users,nik_ktp,' . $user->id,
            'nik_karyawan' => 'required|string|max:255|unique:users,nik_karyawan,' . $user->id,
            'kta_number' => 'required|string|max:255|unique:users,kta_number,' . $user->id,
            'barcode_number' => 'required|string|max:255|unique:users,barcode_number,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string',
            'birth_place' => 'nullable|string',
            'birth_date' => 'nullable|date_format:d/m/Y',
            'joint_date' => 'nullable|date_format:d/m/Y',
            'gender' => 'nullable|in:male,female',
            'religion' => 'nullable|string',
            'education' => 'nullable|string',
            'address' => 'nullable|string',
            'pin' => 'nullable|string|size:6',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'nik_ktp.unique' => 'NIK KTP sudah digunakan.',
            'nik_karyawan.required' => 'NIK Karyawan wajib diisi.',
            'nik_karyawan.unique' => 'NIK Karyawan sudah digunakan.',
            'kta_number.required' => 'KTA wajib diisi.',
            'kta_number.unique' => 'Nomor KTA sudah digunakan oleh member lain.',
            'barcode_number.unique' => 'Nomor barcode sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'pin.size' => 'PIN harus 6 digit.',
        ]);

        // Update data profil
        $birthDate = $request->birth_date ?\Carbon\Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d') : null;
        $jointDate = $request->joint_date ?\Carbon\Carbon::createFromFormat('d/m/Y', $request->joint_date)->format('Y-m-d') : null;

        $user->fill([
            'name' => $request->name,
            'nik_ktp' => $request->nik_ktp,
            'nik_karyawan' => $request->nik_karyawan,
            'kta_number' => $request->kta_number,
            'barcode_number' => $request->barcode_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'birth_place' => $request->birth_place,
            'birth_date' => $birthDate,
            'joint_date' => $jointDate,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'education' => $request->education,
            'address' => $request->address,
        ]);

        // Update PIN jika diisi
        if ($request->filled('pin')) {
            $user->pin = Hash::make($request->pin);
            $user->pin_hint = $request->pin;
        }

        // Update Password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->password_hint = $request->password;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            // 1. Identifikasi Vote di mana user ini adalah kandidat
            $voteIds = VoteOption::where('user_id', $user->id)->pluck('vote_id')->unique();

            // 2. Hapus pendaftaran member (berdasarkan NIK KTP)
            MemberRegistration::where('nik_ktp', $user->nik_ktp)->delete();

            // 3. Hapus data device
            $user->device()->delete();

            // 4. Hapus balasan tiket yang dibuat oleh user ini
            TicketReply::where('user_id', $user->id)->delete();

            // 5. Hapus User Utama
            // Ini akan mentrigger cascade delete di database untuk Ticket, VoteOption, dan VoteResult
            $user->delete();

            // 6. Cek sisa kandidat di setiap Vote terkait
            foreach ($voteIds as $voteId) {
                $remainingCandidates = VoteOption::where('vote_id', $voteId)->count();

                // Jika sisa kandidat kurang dari 2, hapus Vote-nya sekalian
                if ($remainingCandidates < 2) {
                    Vote::where('id', $voteId)->delete();
                }
            }
        });

        return redirect()->route('users.index')->with('success', 'User dan semua data terkait berhasil dihapus.');
    }

    public function generateKta(User $user, \Illuminate\Http\Request $request)
    {
        // 85.6mm x 110.0mm (2 Kartu atas-bawah ngepas tanpa celah bawah) -> 242.64pt x 311.81pt
        $pdf = Pdf::loadView('pdf.kta', compact('user'))
            ->setPaper([0, 0, 242.64, 311.81], 'portrait');

        $filename = 'KTA_' . str_replace(' ', '_', $user->name) . '_' . ($user->kta_number ?? 'nonum') . '.pdf';

        if ($request->query('mode') === 'download') {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'data_anggota_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function downloadTemplate()
    {
        return Excel::download(new UserTemplateExport, 'template_import_pion_anggota.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('users.index')->with('success', 'Data anggota berhasil diimpor.');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $error_msg = 'Terjadi kesalahan pada data Excel: <br>';
            foreach ($failures as $failure) {
                $error_msg .= 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . '<br>';
            }
            return redirect()->route('users.index')->with('error_html', $error_msg);
        }
        catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
