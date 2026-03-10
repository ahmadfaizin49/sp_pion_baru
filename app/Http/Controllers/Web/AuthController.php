<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
        }

        return view('pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember'); // true kalau checkbox dicentang

        if (Auth::attempt($credentials, $remember)) {
            // ✅ cek role langsung saat login
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard');
            }

            // kalau bukan admin, logout dan kasih pesan error
            Auth::logout();
            return back()->with('error', 'Hanya admin yang bisa login.');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
