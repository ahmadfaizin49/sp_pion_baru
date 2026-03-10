<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'name'         => 'required|string|max:255',
            'nik'          => 'required|string|max:255|unique:users,nik',
            'kta_number'   => 'required|string|max:255|unique:users,kta_number',
            'barcode_number' => 'required|string|max:255|unique:users,barcode_number',
            'email'        => 'nullable|email|max:255|unique:users,email',
            'phone'        => 'nullable|string|max:20',
            'department'   => 'nullable|string',
            'birth_place'  => 'nullable|string',
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|in:male,female',
            'religion'     => 'nullable|string',
            'education'    => 'nullable|string',
            'address'      => 'nullable|string',
            'pin'          => 'required|string|size:6',
            'password'     => 'required|string|min:6',
        ], [
            'name.required'       => 'Nama wajib diisi.',
            'nik.required'        => 'NIK wajib diisi.',
            'nik.unique'          => 'NIK sudah digunakan.',
            'kta_number.required' => 'KTA wajib diisi.',
            'kta_number.unique'   => 'Nomor KTA sudah digunakan oleh member lain.',
            'barcode_number.unique' => 'Nomor barcode sudah digunakan.',
            'email.unique'        => 'Email sudah digunakan.',
            'password.required'   => 'Password wajib diisi.',
            'pin.size'            => 'PIN harus 6 digit.',
        ]);

        User::create([
            'name'         => $request->name,
            'nik'          => $request->nik,
            'username'     => $request->nik,
            'kta_number'   => $request->kta_number,
            'barcode_number' => $request->barcode_number,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'department'   => $request->department,
            'birth_place'  => $request->birth_place,
            'birth_date'   => $request->birth_date,
            'gender'       => $request->gender,
            'religion'     => $request->religion,
            'education'    => $request->education,
            'address'      => $request->address,
            'role'         => 'user',
            'pin'          => Hash::make($request->pin),
            'password'     => Hash::make($request->password),
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
            'name'         => 'required|string|max:255',
            'nik'          => 'required|string|max:255|unique:users,nik,' . $user->id,
            'kta_number'   => 'required|string|max:255|unique:users,kta_number,' . $user->id,
            'barcode_number' => 'required|string|max:255|unique:users,barcode_number,' . $user->id,
            'email'        => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone'        => 'nullable|string|max:20',
            'department'   => 'nullable|string',
            'birth_place'  => 'nullable|string',
            'birth_date'   => 'nullable|date',
            'gender'       => 'nullable|in:male,female',
            'religion'     => 'nullable|string',
            'education'    => 'nullable|string',
            'address'      => 'nullable|string',
            'pin'          => 'nullable|string|size:6',
            'password'     => 'nullable|string|min:6',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'nik.required'      => 'NIK wajib diisi.',
            'nik.unique'        => 'NIK sudah digunakan.',
            'kta_number.required' => 'KTA wajib diisi.',
            'kta_number.unique' => 'Nomor KTA sudah digunakan oleh member lain.',
            'barcode_number.unique' => 'Nomor barcode sudah digunakan.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'pin.size'          => 'PIN harus 6 digit.',
        ]);

        // Update data profil
        $user->fill([
            'name'         => $request->name,
            'nik'          => $request->nik,
            'kta_number'   => $request->kta_number,
            'barcode_number' => $request->barcode_number,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'department'   => $request->department,
            'birth_place'  => $request->birth_place,
            'birth_date'   => $request->birth_date,
            'gender'       => $request->gender,
            'religion'     => $request->religion,
            'education'    => $request->education,
            'address'      => $request->address,
        ]);

        // Update PIN jika diisi
        if ($request->filled('pin')) {
            $user->pin = Hash::make($request->pin);
        }

        // Update Password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
