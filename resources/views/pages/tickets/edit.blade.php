@extends('layouts.master')

@section('title')
    Reply Pesan
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Reply Pesan</h5>
                        <a class="btn btn-primary" href="{{ route('tickets.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Back
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
                                        <span class="badge bg-danger">Report</span>
                                    @elseif($ticket->type == 'question')
                                        <span class="badge bg-info">Question</span>
                                    @else
                                        <span class="badge bg-secondary">Suggestion</span>
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
                    <div class="card-body" style="height: 450px; overflow-y: auto; background-color: #f0f2f5;">

                        <div class="d-flex justify-content-start mb-4">
                            <div class="bg-white p-3 rounded shadow-sm border" style="max-width: 75%;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-soft-primary text-primary px-0">Pesan Awal</span>
                                    <small class="text-muted ms-3">{{ $ticket->created_at->format('d M, H:i') }}</small>
                                </div>
                                <p class="mb-2 fw-bold text-dark">{{ $ticket->title }}</p>
                                <p class="mb-0">{{ $ticket->description }}</p>

                                @if ($ticket->attachment)
                                    <div class="mt-2 pt-2 border-top">
                                        <small class="text-muted d-block mb-1">Lampiran:</small>
                                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="fa fa-paperclip"></i> Lihat Lampiran
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @foreach ($ticket->replies as $reply)
                            @php $isMe = $reply->user_id == Auth::id(); @endphp
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
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('tickets.reply', $ticket->id) }}" class="form theme-form">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="fw-bold">Reply Pesan Kamu</label>
                                        <textarea class="form-control" name="message" rows="4" placeholder="Tulis pesan balasan atau solusi..." required>{{ old('message') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold">Update Status Pesan</label>
                                        <select class="form-select" name="status" required>
                                            <option value="responded"
                                                {{ $ticket->status == 'responded' ? 'selected' : '' }}>Responded (Tetap
                                                Aktif)</option>
                                            <option value="processed"
                                                {{ $ticket->status == 'processed' ? 'selected' : '' }}>Processed</option>
                                            <option value="done" {{ $ticket->status == 'done' ? 'selected' : '' }}>Done
                                                (Masalah Selesai)</option>
                                            <option value="rejected" {{ $ticket->status == 'rejected' ? 'selected' : '' }}>
                                                Rejected</option>
                                        </select>
                                        <small class="text-muted italic">* Pilih 'Done' jika tidak ada lagi yang perlu
                                            dibahas.</small>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end justify-content-end">
                                    <div class="mb-3">
                                        <button class="btn btn-success px-5 py-2" type="submit">
                                            <i class="fa fa-paper-plane me-2"></i> Send Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
