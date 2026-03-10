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
        $tickets = Ticket::with('user')->latest()->get();
        return view('pages.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('user');
        return view('pages.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('pages.tickets.edit', compact('ticket'));
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
                'Tiket #' . $ticket->ticket_number,
                'Admin: ' . Str::limit($request->message, 50),
                [
                    'id' => (string) $ticket->id,
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
                'Tiket #' . $ticket->ticket_number, // TITLE
                'Admin telah menanggapi: ' . Str::limit($request->admin_response, 50), // BODY
                [
                    'id' => (string) $ticket->id,
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
        // user = pemilik tiket, replies.user = pengirim chat
        $ticket->load(['user', 'replies.user']);

        // 2. Generate PDF dari view yang dibuat tadi
        $pdf = Pdf::loadView('pdf.ticket_report', compact('ticket'));

        // 3. Atur ukuran kertas dan orientasi
        $pdf->setPaper('a4', 'portrait');

        // 4. Return stream (bukan download) agar bisa dilihat di browser/previewer
        return $pdf->stream('Laporan-Tiket-' . $ticket->ticket_number . '.pdf');
    }
}
