<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\DB;

class BroadcastController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Tampilkan daftar broadcast
     */
    public function index()
    {
        $broadcasts = Broadcast::latest()->get();
        return view('pages.broadcasts.index', compact('broadcasts'));
    }

    /**
     * Tampilkan form buat broadcast baru
     */
    public function create()
    {
        // Ambil semua user role 'user'
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('pages.broadcasts.create', compact('users'));
    }

    /**
     * Simpan broadcast & kirim notifikasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'users' => 'nullable|array', // optional
        ]);

        DB::transaction(function () use ($request) {

            // Simpan broadcast
            $broadcast = Broadcast::create([
                'title' => $request->title,
                'body' => $request->body,
            ]);

            // Ambil user yang dipilih
            $selectedUsers = $request->users ?? [];

            if (count($selectedUsers) > 0) {
                $broadcast->users()->attach($selectedUsers);
                $tokens = User::whereIn('id', $selectedUsers)
                    ->whereNotNull('fcm_token')
                    ->pluck('fcm_token')
                    ->toArray();
            } else {
                // Kalau tidak pilih user → broadcast ke semua
                $tokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
            }

            // Kirim notifikasi
            $this->firebase->sendToTokens($tokens, $request->title, $request->body);
        });

        return redirect()->route('broadcasts.index')->with('success', 'Broadcast berhasil dibuat dan dikirim.');
    }


    /**
     * Hapus broadcast
     */
    public function destroy(Broadcast $broadcast)
    {
        $broadcast->users()->detach();
        $broadcast->delete();

        return redirect()->route('broadcasts.index')
            ->with('success', 'Broadcast berhasil dihapus.');
    }
}
