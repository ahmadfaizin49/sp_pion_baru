<?php

namespace App\Http\Controllers\Api;

use App\Models\MemberRegistration;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberRegistrationController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');

        // 1. Build Query (Cuma ambil data yang diinput oleh user yang login)
        $query = MemberRegistration::where('referrer_id', $user->id)
            ->latest();

        // 2. Apply Search (Cari berdasarkan nama atau nik pendaftar)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nik_ktp', 'like', "%{$search}%")
                    ->orWhere('nik_karyawan', 'like', "%{$search}%");
            });
        }

        // 3. Paginate (Ambil 10 data per halaman)
        $registrations = $query->paginate(10);

        // 4. Map data biar rapih (Hanya kirim field yang perlu ditampilkan di list)
        $data = $registrations->map(function ($reg) {
            return [
                'id'     => $reg->id,
                'name'         => $reg->name,
                'nik_ktp'      => $reg->nik_ktp,
                'nik_karyawan' => $reg->nik_karyawan,
                'status'       => $reg->status,
                'created_at' => $reg->created_at,
            ];
        });

        // 5. Return Response dengan Meta
        return response()->json([
            'status'  => true,
            'message' => 'Registration member fetched successfully',
            'data'    => $data,
            'meta'    => [
                'current_page'  => $registrations->currentPage(),
                'last_page'     => $registrations->lastPage(),
                'per_page'      => $registrations->perPage(),
                'total'         => $registrations->total(),
                'next_page_url' => $registrations->nextPageUrl(),
                'prev_page_url' => $registrations->previousPageUrl(),
            ],
        ], 200);
    }

    public function show(MemberRegistration $member)
    {
        return response()->json([
            'status'  => true,
            'message' => 'Member registration fetched successfully',
            'data'    => $member
        ]);
    }

    /**
     * Fitur JOIN ANGGOTA (Input dari Flutter)
     */
    public function store(Request $request)
    {
        $nikExist = User::where('nik_ktp', $request->nik_ktp)->exists()
            || User::where('nik_karyawan', $request->nik_karyawan)->exists()
            || MemberRegistration::where('nik_ktp', $request->nik_ktp)->exists()
            || MemberRegistration::where('nik_karyawan', $request->nik_karyawan)->exists();

        if ($nikExist) {
            return response()->json([
                'status'  => false,
                'message' => 'NIK ini sudah terdaftar',
            ], 422);
        }

        $registration = MemberRegistration::create([
            'referrer_id' => Auth::id(),
            'name' => $request->name,
            'nik_ktp' => $request->nik_ktp,
            'nik_karyawan' => $request->nik_karyawan,
            'department' => $request->department,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'education' => $request->education,
            'phone' => $request->phone,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registration member submitted successfully!',
            'data' => $registration
        ], 201);
    }
}
