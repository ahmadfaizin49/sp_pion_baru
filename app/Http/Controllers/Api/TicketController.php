<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // 1️⃣ List semua tiket milik user
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');

        // Build query
        $query = Ticket::where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Apply search di title (opsional)
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Paginate
        $tickets = $query->paginate(10);

        // Map hanya field penting
        $data = $tickets->map(function ($ticket) {
            return [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'type' => $ticket->type,
                'title' => $ticket->title,
                'status' => $ticket->status,
                'created_at' => $ticket->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Tickets fetched successfully',
            'data' => $data,
            'meta' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total(),
                'next_page_url' => $tickets->nextPageUrl(),
            ],
        ]);
    }


    // 2️⃣ show (MODIFIED: Sekarang mengikutsertakan replies)
    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        // MODIFIED: Load replies DAN user yang punya reply tersebut
        $ticket->load(['replies' => function ($query) {
            $query->orderBy('created_at', 'asc')->with('user:id,name,role'); // Ambil field penting aja
        }]);

        return response()->json([
            'status' => true,
            'message' => 'Detail tiket berhasil diambil',
            'data' => [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'type' => $ticket->type,
                'title' => $ticket->title,
                'description' => $ticket->description,
                'status' => $ticket->status,
                'attachment_url' => $ticket->attachment ? url('storage/' . $ticket->attachment) : null,
                // Map replies supaya strukturnya enak dibaca Flutter
                'replies' => $ticket->replies->map(function ($reply) {
                    return [
                        'id' => $reply->id,
                        'user_id' => $reply->user_id,
                        'user_name' => $reply->user->name,
                        'role' => $reply->user->role, // INI YANG KAMU MAU
                        'message' => $reply->message,
                        'created_at' => $reply->created_at,
                    ];
                }),
                'created_at' => $ticket->created_at,
            ],
        ]);
    }

    // 3️⃣ reply (NEW: Endpoint khusus untuk chatting dari sisi user/Flutter)
    public function reply(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->user_id !== $user->id) {
            return response()->json(['status' => false, 'message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        // Simpan balasan chat dari user
        $reply = $ticket->replies()->create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        // Ubah status ke pending agar Admin tahu ada pesan baru dari user
        $ticket->update(['status' => 'pending']);

        return response()->json([
            'status' => true,
            'message' => 'Balasan berhasil dikirim',
            'data' => $reply
        ], 201);
    }


    // 3️⃣ Submit tiket baru
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:report,question,suggestion',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // max 2MB
        ]);

        $user = Auth::user();

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            // simpan file di storage/app/public/tickets
            $attachmentPath = $file->store('tickets', 'public');
        }

        $ticket = Ticket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'user_id' => $user->id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tiket baru berhasil dibuat',
            'data' => $ticket,
        ], 201);
    }

    // 4️⃣ Update tiket (untuk balas/tambah keterangan)
    public function update(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        // Pastikan ini tiket milik dia
        if ($ticket->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $request->validate([
            'description' => 'required|string',
            'status' => 'nullable|string'
        ]);

        // Update data
        $ticket->update([
            'description' => $request->description,
            // Jika ada input status (misal mau re-open), pakai itu.
            // Kalau nggak, tetapkan 'pending' lagi karena ada update baru dari user.
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Tiket berhasil diperbarui',
            'data' => $ticket,
        ]);
    }

    // 🔹 Helper generate ticket_number
    private function generateTicketNumber()
    {
        $last = Ticket::latest('id')->first();
        $number = $last ? $last->id + 1 : 1;
        return 'TCK-' . now()->year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
