<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Services\FirebaseService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        // Ambil ticket dengan hitungan reply yang belum dibaca (is_read = false) dari sisi user (role user)
        // Ditambah dengan status is_read dari tiket itu sendiri
        $tickets = Ticket::with(['user'])
            ->withCount(['replies as unread_replies_count' => function ($query) {
            $query->where('is_read', false)
                ->whereHas('user', function ($q) {
                $q->where('role', 'user');
            }
            );
        }])
            ->latest()
            ->get();

        $tickets->map(function ($ticket) {
            $ticket->unread_count = $ticket->unread_replies_count + ($ticket->is_read ? 0 : 1);
            return $ticket;
        });

        return view('pages.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('user');
        return view('pages.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        // Tandai tiket itu sendiri sebagai terbaca
        if (!$ticket->is_read) {
            $ticket->update(['is_read' => true]);
        }

        // Tandai semua pesan dari user (bukan admin) sebagai terbaca
        $ticket->replies()
            ->where('is_read', false)
            ->whereHas('user', function ($query) {
            $query->where('role', 'user');
        })
            ->update(['is_read' => true]);

        return view('pages.tickets.edit', compact('ticket'));
    }

    public function getReplies(Request $request, Ticket $ticket)
    {
        // Mendapatkan JSON reply yang ID-nya lebih besar dari ID terakhir di Client
        $lastId = $request->query('last_id', 0);

        $newReplies = $ticket->replies()
            ->with('user')
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        $formattedReplies = $newReplies->map(function ($reply) {
            $isMe = $reply->user_id == Auth::id();
            return [
            'id' => $reply->id,
            'message' => $reply->message,
            'sender' => $isMe ? 'You (Admin)' : ($reply->user->name ?? 'User'),
            'date' => $reply->created_at->format('d M, H:i'),
            'is_me' => $isMe,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formattedReplies
        ]);
    }

    // MODIFIED: Fungsi untuk memproses balasan (Chat) dari Admin
    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string', // Sesuai dengan name="message" di blade
            'status' => 'required|in:pending,responded,processed,done,rejected',
        ], [
            'message.required' => 'Pesan balasan wajib diisi.',
        ]);

        // 1. Simpan ke tabel ticket_replies (History Chat)
        $ticket->replies()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // 2. Update status tiket dan admin_response (untuk kompatibilitas data lama)
        $ticket->update([
            'admin_response' => $request->message, // Tetap diisi agar record lama tidak kosong
            'status' => $request->status,
            'responded_at' => now(),
        ]);

        // 3. KIRIM NOTIFIKASI FIREBASE KE USER
        $user = User::find($ticket->user_id);
        if ($user && !empty($user->fcm_token)) {
            $this->firebase->sendToTokens(
            [$user->fcm_token],
                'Pesan',
                'Admin: ' . Str::limit($request->message, 50),
            [
                'id' => (string)$ticket->id,
                'type' => 'ticket_response',
                'status' => $ticket->status,
            ]
            );
        }

        return redirect()->back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'admin_response' => 'required|string',
            'status' => 'required|in:pending,responded,processed,done,rejected',
        ], [
            'admin_response.required' => 'Tanggapan admin wajib diisi.',
        ]);

        $ticket->update([
            'admin_response' => $request->admin_response,
            'status' => $request->status,
            'responded_at' => now(),
        ]);

        // ---------- KIRIM NOTIF KE USER TERKAIT ----------
        $user = User::find($ticket->user_id);

        if ($user && !empty($user->fcm_token)) {
            $this->firebase->sendToTokens(
            [$user->fcm_token],
                'Pesan',
                'Admin telah menanggapi: ' . Str::limit($request->admin_response, 50), // BODY
            [
                'id' => (string)$ticket->id,
                'type' => 'ticket_response', // 👈 buat routing di Flutter
                'status' => $ticket->status,
            ]
            );
        }

        return redirect()->route('tickets.index')->with('success', 'Tanggapan berhasil dikirim dan user telah dinotifikasi.');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->attachment) {
            Storage::disk('public')->delete($ticket->attachment);
        }

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dihapus.');
    }

    public function previewPdf(Ticket $ticket)
    {
        // 1. Load data relasi yang dibutuhkan
        $ticket->load(['user', 'replies.user']);

        // 2. Ambil setting email organisasi
        $emailOrganisasi = \App\Models\Setting::get(\App\Models\Setting::EMAIL_ORGANISASI, 'sppion18@gmail.com');

        // 3. Generate PDF dari view
        $pdf = Pdf::loadView('pdf.ticket_report', compact('ticket', 'emailOrganisasi'));

        // 4. Atur ukuran kertas
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Tiket-' . $ticket->ticket_number . '.pdf');
    }

    public function getUnreadData()
    {
        $tickets = Ticket::withCount(['replies as unread_replies_count' => function ($query) {
            $query->where('is_read', false)
                ->whereHas('user', function ($q) {
                $q->where('role', 'user');
            }
            );
        }])->get(['id', 'status', 'is_read']);

        $totalTicketsCount = Ticket::count();

        return response()->json([
            'status' => 'success',
            'total_tickets_count' => $totalTicketsCount,
            'data' => $tickets->map(function ($ticket) {
            return [
                    'id' => $ticket->id,
                    'unread_count' => $ticket->unread_replies_count + ($ticket->is_read ? 0 : 1),
                    'status' => $ticket->status,
                ];
        })
        ]);
    }
}
