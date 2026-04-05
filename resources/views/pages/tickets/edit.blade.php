@extends('layouts.master')

@section('title')
    Reply Pesan
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Reply Pesan</h5>
                        <a class="btn btn-primary" href="{{ route('tickets.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Nama</label>
                                <p class="fw-bold">{{ $ticket->user->name }}</p>

                                <label class="text-muted small">Tipe</label>
                                <p>
                                    @if ($ticket->type == 'report')
                                        <span class="badge badge-report">Report</span>
                                    @elseif($ticket->type == 'question')
                                        <span class="badge badge-question">Question</span>
                                    @else
                                        <span class="badge badge-suggestion">Suggestion</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Judul</label>
                                <p class="fw-bold">{{ $ticket->title ?? '-' }}</p>

                                <label class="text-muted small">Tanggal</label>
                                <p class="fw-bold">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card shadow-none border">
                    <div class="card-header">
                        <h6 class="mb-0 fw-bold">Riwayat Percakapan</h6>
                    </div>
                    <div class="card-body" id="chat-container" style="height: 450px; overflow-y: auto; background-color: #f0f2f5;">

                        <div class="d-flex justify-content-start mb-4">
                            <div class="bg-white p-3 rounded shadow-sm border" style="max-width: 75%;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-primary">Pesan Awal</span>
                                    <small class="text-muted ms-3">{{ $ticket->created_at->format('d M, H:i') }}</small>
                                </div>
                                <p class="mb-2 fw-bold text-dark">{{ $ticket->title }}</p>
                                <p class="mb-0">{{ $ticket->description }}</p>

                                @if ($ticket->attachment)
                                    <div class="mt-2 pt-2 border-top">
                                        <small class="text-muted d-block mb-1">Lampiran:</small>
                                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                            class="btn-premium btn-premium-success">
                                            <i class="fa fa-paperclip"></i> Lihat Lampiran
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @php $lastReplyId = 0; @endphp
                        @foreach ($ticket->replies as $reply)
                            @php 
                                $isMe = $reply->user_id == Auth::id(); 
                                $lastReplyId = $reply->id;
                            @endphp
                            <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-4">
                                <div class="{{ $isMe ? 'bg-primary text-white' : 'bg-white border' }} p-3 rounded shadow-sm"
                                    style="max-width: 75%;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-bold {{ $isMe ? 'text-white-50' : 'text-primary' }}">
                                            {{ $isMe ? 'You (Admin)' : $ticket->user->name }}
                                        </small>
                                        <small class="ms-3 {{ $isMe ? 'text-white-50' : 'text-muted' }}"
                                            style="font-size: 10px;">
                                            {{ $reply->created_at->format('d M, H:i') }}
                                        </small>
                                    </div>
                                    <p class="mb-0">{{ $reply->message }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-soft-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" id="reply-form" action="{{ route('tickets.reply', $ticket->id) }}" class="form theme-form">
                            @csrf
                            <!-- Input Reply -->
                            <div class="mb-3">
                                <label class="fw-bold">Reply Pesan Kamu</label>
                                <textarea class="form-control" name="message" id="reply-message" rows="4" placeholder="Tulis pesan balasan atau solusi..." required>{{ old('message') }}</textarea>
                            </div>

                            <!-- Status and Button -->
                            <div class="mb-3">
                                <label class="fw-bold">Update Status Pesan</label>
                                <select class="form-select mb-2" name="status" required>
                                    <option value="responded" {{ $ticket->status == 'responded' ? 'selected' : '' }}>
                                        Responded (Masih Aktif)</option>
                                    <option value="processed" {{ $ticket->status == 'processed' ? 'selected' : '' }}>
                                        Processed (Sedang Ditangani)</option>
                                    <option value="done" {{ $ticket->status == 'done' ? 'selected' : '' }}>
                                        Done (Selesai)</option>
                                    <option value="rejected" {{ $ticket->status == 'rejected' ? 'selected' : '' }}>
                                        Rejected (Ditolak)</option>
                                </select>
                                <small class="text-muted italic d-block mb-3">* Pilih 'Done' jika tidak ada lagi yang perlu
                                    dibahas.</small>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-success px-5 py-2" type="submit">
                                    <i class="fa fa-paper-plane me-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let lastReplyId = {{ $lastReplyId }};
        const ticketId = {{ $ticket->id }};
        const chatContainer = document.getElementById('chat-container');

        // Fungsi scroll ke bawah
        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Scroll saat halaman dimuat
        scrollToBottom();

        // Polling pesan baru setiap 3 detik
        setInterval(function() {
            fetch(`/tickets/${ticketId}/replies?last_id=${lastReplyId}`)
                .then(response => response.json())
                .then(result => {
                    if (result.status === 'success' && result.data.length > 0) {
                        result.data.forEach(reply => {
                            // Render template chat bubble
                            const alignmentClass = reply.is_me ? 'justify-content-end' : 'justify-content-start';
                            const bubbleClass = reply.is_me ? 'bg-primary text-white' : 'bg-white border';
                            const nameColorClass = reply.is_me ? 'text-white-50' : 'text-primary';
                            const timeColorClass = reply.is_me ? 'text-white-50' : 'text-muted';

                            const html = `
                                <div class="d-flex ${alignmentClass} mb-4">
                                    <div class="${bubbleClass} p-3 rounded shadow-sm" style="max-width: 75%;">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="fw-bold ${nameColorClass}">${reply.sender}</small>
                                            <small class="ms-3 ${timeColorClass}" style="font-size: 10px;">${reply.date}</small>
                                        </div>
                                        <p class="mb-0">${reply.message}</p>
                                    </div>
                                </div>
                            `;
                            chatContainer.insertAdjacentHTML('beforeend', html);
                            lastReplyId = reply.id; // Update last_id
                        });
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error polling replies:', error));
        }, 5000);
    });
</script>
@endpush
