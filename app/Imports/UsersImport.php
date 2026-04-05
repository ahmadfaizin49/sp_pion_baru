<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows, WithChunkReading
{
    /**
     * Cache hashes to avoid repeated slow hashing of default values
     */
    protected $passwordCache = [];
    protected $defaultFallbackDate;

    public function __construct()
    {
        $this->defaultFallbackDate = now()->format('Y-m-d');
    }

    /**
     * Parse date from various formats sensitively to avoid nulls
     */
    private function parseDateSafely($value)
    {
        if (empty($value)) {
            return $this->defaultFallbackDate;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
            }

            // Try common formats to avoid ambiguity
            $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'Y/m/d', 'm/d/Y'];
            foreach ($formats as $fmt) {
                try {
                    $d = Carbon::createFromFormat($fmt, (string)$value);
                    if ($d && $d->format($fmt) === (string)$value) {
                        return $d->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Fallback to general parsing
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->defaultFallbackDate;
        }
    }

    public function collection(Collection $rows)
    {
        // 1. Kumpulkan semua identifier (KTP, NIK, KTA) dari file untuk pengecekan bulk
        $nikKtps = $rows->pluck('ktp')->filter()->toArray();
        $nikKaryawans = $rows->pluck('nik')->filter()->map(function ($v) {
            return (string) $v;
        })->toArray();
        $ktaNumbers = $rows->pluck('kta')->filter()->toArray();

        // 2. Ambil data existing dari DB dalam satu query (Bulk Fetch) untuk efisiensi
        $existingUsers = User::whereIn('nik_ktp', $nikKtps)
            ->orWhereIn('nik_karyawan', $nikKaryawans)
            ->orWhereIn('kta_number', $ktaNumbers)
            ->get();

        // Map existing untuk pencarian cepat (O(1) lookup)
        $existingKtps = $existingUsers->pluck('nik_ktp')->filter()->flip()->toArray();
        $existingNiks = $existingUsers->pluck('nik_karyawan')->filter()->flip()->toArray();
        $existingKtas = $existingUsers->pluck('kta_number')->filter()->flip()->toArray();

        // Gunakan Transaction untuk mempercepat banyak operasi INSERT
        DB::transaction(function () use ($rows, &$existingKtps, &$existingNiks, &$existingKtas) {
            foreach ($rows as $row) {
                // Skip jika nama kosong (baris kosong di bawah data)
                if (empty($row->get('nama'))) {
                    continue;
                }

                // --- Check if exists in DB or already processed in this batch ---
                $ktp = $row->get('ktp');
                $nik = (string) ($row->get('nik') ?? '');
                $kta = $row->get('kta');

                // Logika "Skip if exists"
                if (
                    ($ktp && isset($existingKtps[$ktp])) ||
                    ($nik && isset($existingNiks[$nik])) ||
                    ($kta && isset($existingKtas[$kta]))
                ) {
                    continue;
                }

                // Tambahkan ke map "existing" agar baris duplikat di DALAM file yang sama juga ter-skip
                if ($ktp)
                    $existingKtps[$ktp] = true;
                if ($nik)
                    $existingNiks[$nik] = true;
                if ($kta)
                    $existingKtas[$kta] = true;

                // --- Proses Tanggal Lahir ---
                $birthDate = $this->parseDateSafely($row->get('tanggal_lahir'));

                // --- Proses Jenis Kelamin ---
                $gender = null;
                $jenisKelamin = $row->get('jenis_kelamin');
                if (!empty($jenisKelamin)) {
                    $val = strtolower($jenisKelamin);
                    $gender = (str_contains($val, 'laki')) ? 'male' : 'female';
                }

                // --- Proses Joint Date ---
                $jointDate = $this->parseDateSafely($row->get('joint_date'));

                // --- Proses Hash (OPTIMIZED: Cache hashes) ---
                $pinRaw = (string) ($row->get('pin') ?? '123456');
                $passRaw = (string) ($row->get('password') ?? 'password123');

                if (!isset($this->passwordCache['pin_' . $pinRaw])) {
                    $this->passwordCache['pin_' . $pinRaw] = Hash::make($pinRaw);
                }
                if (!isset($this->passwordCache['pass_' . $passRaw])) {
                    $this->passwordCache['pass_' . $passRaw] = Hash::make($passRaw);
                }

                User::create([
                    'name' => $row->get('nama'),
                    'nik_ktp' => $ktp,
                    'nik_karyawan' => $nik,
                    'username' => $ktp,
                    'kta_number' => $kta,
                    'barcode_number' => $row->get('barcode'),
                    'email' => $row->get('email'),
                    'department' => $row->get('bagian'),
                    'phone' => $row->get('no_telepon') ? (string) $row->get('no_telepon') : null,
                    'birth_place' => $row->get('tempat_lahir'),
                    'birth_date' => $birthDate,
                    'joint_date' => $jointDate,
                    'gender' => $gender,
                    'religion' => $row->get('agama'),
                    'education' => $row->get('pendidikan'),
                    'address' => $row->get('alamat'),
                    'role' => 'user',
                    'pin' => $this->passwordCache['pin_' . $pinRaw],
                    'password' => $this->passwordCache['pass_' . $passRaw],
                    'pin_hint' => $pinRaw,
                    'password_hint' => $passRaw,
                ]);
            }
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }


    public function rules(): array
    {
        /**
         * Menggunakan 'sometimes' agar jika baris tidak lengkap, 
         * kita tidak menghentikan seluruh proses import.
         */
        return [
            'nik' => 'sometimes|required',
            'kta' => 'sometimes|required',
            'nama' => 'sometimes|required|string|max:255',
            'ktp' => 'sometimes|nullable|string|max:255',
            'alamat' => 'sometimes|required',
            'tempat_lahir' => 'sometimes|required',
            'tanggal_lahir' => 'sometimes|required',
            'joint_date' => 'sometimes|required',
            'jenis_kelamin' => 'sometimes|required',
            'bagian' => 'sometimes|required',
            'agama' => 'sometimes|required',
            'email' => 'sometimes|nullable',
            'pendidikan' => 'sometimes|required',
            'no_telepon' => 'sometimes|nullable',
            'barcode' => 'sometimes|required',
            'pin' => 'sometimes|required',
            'password' => 'sometimes|required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'kta.required' => 'KTA wajib diisi.',
            'nama.required' => 'NAMA wajib diisi.',
            'alamat.required' => 'ALAMAT wajib diisi.',
            'tempat_lahir.required' => 'TEMPAT LAHIR wajib diisi.',
            'tanggal_lahir.required' => 'TANGGAL LAHIR wajib diisi.',
            'joint_date.required' => 'JOINT DATE wajib diisi.',
            'jenis_kelamin.required' => 'JENIS KELAMIN wajib diisi.',
            'bagian.required' => 'BAGIAN wajib diisi.',
            'agama.required' => 'AGAMA wajib diisi.',
            'pendidikan.required' => 'PENDIDIKAN wajib diisi.',
            'barcode.required' => 'BARCODE wajib diisi.',
            'pin.required' => 'PIN wajib diisi.',
            'password.required' => 'PASSWORD wajib diisi.',
        ];
    }
}
