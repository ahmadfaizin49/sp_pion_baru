@extends('layouts.master')

@section('title')
    Data Pesan
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
        .table-responsive table {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Styling Notifikasi Tiket Baru (Toast Premium) */
        .new-ticket-toast-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            width: 320px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-left: 5px solid #AA2224;
            overflow: hidden;
        }

        .dark-only .new-ticket-toast-container {
            background: rgba(30, 31, 34, 0.95);
            border-left: 5px solid #AA2224;
            color: #fff;
        }

        .toast-content {
            display: flex;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .toast-icon {
            width: 50px;
            height: 50px;
            background: rgba(170, 34, 36, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .toast-icon i {
            font-size: 20px;
            color: #AA2224;
        }

        .toast-body h6 {
            margin: 0 0 5px 0;
            font-weight: 700;
            font-size: 16px;
        }

        .toast-body p {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #666;
        }

        .dark-only .toast-body p {
            color: #ccc;
        }

        .btn-refresh-now {
            border-radius: 8px !important;
            padding: 5px 15px !important;
            font-weight: 600 !important;
            transition: all 0.3s ease;
            background-color: #AA2224 !important;
            border-color: #AA2224 !important;
        }

        .btn-refresh-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(170, 34, 36, 0.3);
            background-color: #8a1b1d !important;
            border-color: #8a1b1d !important;
        }

        .btn-close-toast {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #999;
            font-size: 14px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .btn-close-toast:hover {
            color: #ff4d4d;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Create -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Data Pesan</h5>
                        <button class="btn btn-success btn-xs" onclick="playNotificationSound()"
                            title="Cek Suara Notifikasi">
                            <i class="fa fa-volume-up me-1"></i> Cek Suara
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Alert sukses --}}
                        @if (session('success'))
                            <div class="alert alert-soft-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Table untuk list tickets --}}
                        @if ($tickets->count() > 0)
                            <div class="table-responsive">
                                <table class="display" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th class="dt-col-no">No</th>
                                            <th>Nama</th>
                                            <th>Tipe</th>
                                            <th>Status</th>
                                            <th>Tanggal Pesan</th>
                                            <th>PDF</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr id="ticket-row-{{ $ticket->id }}">
                                                <td class="dt-col-no">{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $ticket->user->name }}
                                                    <span id="unread-badge-container-{{ $ticket->id }}">
                                                        @if ($ticket->unread_count > 0)
                                                            <span class="badge rounded-pill bg-danger ms-1"
                                                                style="font-size: 10px;">
                                                                {{ $ticket->unread_count }} Pesan Baru
                                                            </span>
                                                        @endif
                                                    </span>
                                                </td>

                                                {{-- Badge untuk TYPE --}}
                                                <td>
                                                    @if ($ticket->type == 'report')
                                                        <span class="badge badge-report">Report</span>
                                                    @elseif($ticket->type == 'question')
                                                        <span class="badge badge-question">Question</span>
                                                    @else
                                                        <span class="badge badge-suggestion">Suggestion</span>
                                                    @endif
                                                </td>


                                                {{-- Badge untuk STATUS --}}
                                                <td id="status-container-{{ $ticket->id }}">
                                                    @switch($ticket->status)
                                                        @case('pending')
                                                            <span class="badge badge-pending">Pending</span>
                                                        @break

                                                        @case('responded')
                                                            <span class="badge badge-responded">Responded</span>
                                                        @break

                                                        @case('processed')
                                                            <span class="badge badge-processed">Processed</span>
                                                        @break

                                                        @case('done')
                                                            <span class="badge badge-done">Done</span>
                                                        @break

                                                        @case('rejected')
                                                            <span class="badge badge-rejected">Rejected</span>
                                                        @break
                                                    @endswitch
                                                </td>

                                                <td>{{ $ticket->created_at->format('d/m/y H:i') }}</td>



                                                <td>
                                                    <a href="{{ route('tickets.pdf', $ticket->id) }}"
                                                        class="btn-premium btn-premium-success" target="_blank">
                                                        <i class="fa fa-paperclip"></i> Dengan Lampiran
                                                    </a>

                                                    <a href="{{ route('tickets.pdf', $ticket->id) }}?hide_attachment=1"
                                                        class="btn-premium btn-premium-warning" target="_blank">
                                                        <i class="fa fa-file-text-o"></i> Tanpa Lampiran
                                                    </a>

                                                    @if ($ticket->attachment)
                                                        <a href="{{ url('storage/' . $ticket->attachment) }}"
                                                            target="_blank" class="btn-premium btn-premium-light">
                                                            <i class="fa fa-eye"></i> Lihat Lampiran
                                                        </a>
                                                    @endif
                                                </td>

                                                <td>
                                                    <!-- Reply button -->
                                                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                                                        class="btn btn-success btn-xs">
                                                        Balas
                                                    </a>

                                                    <!-- Delete button -->
                                                    <a href="#" class="btn btn-danger btn-xs" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-action="{{ route('tickets.destroy', $ticket->id) }}"
                                                        data-name="{{ $ticket->name }}">
                                                        Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center p-5">
                                <span class="text-muted">Tidak ada data pesan</span>
                            </div>
                        @endif
                        {{-- End Table --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete (global) --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pesan ini <strong id="deleteItemName"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal Delete --}}



    @push('scripts')
        <!-- Script delete -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteModal = document.getElementById('deleteModal');
                const deleteForm = document.getElementById('deleteForm');
                const deleteItemName = document.getElementById('deleteItemName');

                document.querySelectorAll('.btn-danger[data-bs-target="#deleteModal"]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        deleteForm.action = this.dataset.action;
                        deleteItemName.textContent = this.dataset.name;
                    });
                });
            });
        </script>

        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

        <script>
            let currentTotalTickets = {{ $tickets->count() }};
            let currentUnreadTotal = {{ $tickets->sum('unread_count') }};

            // Fungsi untuk memutar suara
            function playNotificationSound() {
                const audio = new Audio("{{ asset('assets/audio/notification.mp3') }}");
                audio.play().catch(e => {
                    console.log('Autoplay blocked atau error audio:', e);
                    // Jika diblokir, beri info sekali saja di console
                });
            }

            // Polling untuk update status dan unread count secara real-time
            function fetchUnreadData() {
                fetch("{{ route('tickets.unread.data') }}")
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            let newUnreadTotal = 0;

                            // 0. Deteksi Tiket Baru
                            if (result.total_tickets_count > currentTotalTickets) {
                                showNewTicketAlert();
                                currentTotalTickets = result.total_tickets_count;
                            }

                            result.data.forEach(ticket => {
                                newUnreadTotal += ticket.unread_count;

                                // 1. Update Unread Badge
                                const badgeContainer = document.getElementById(
                                    `unread-badge-container-${ticket.id}`);
                                if (badgeContainer) {
                                    if (ticket.unread_count > 0) {
                                        badgeContainer.innerHTML = `
                                            <span class="badge rounded-pill bg-danger ms-1" style="font-size: 10px;">
                                                ${ticket.unread_count} Pesan Baru
                                            </span>
                                        `;
                                    } else {
                                        badgeContainer.innerHTML = '';
                                    }
                                }

                                // 2. Update Status Badge
                                const statusContainer = document.getElementById(`status-container-${ticket.id}`);
                                if (statusContainer) {
                                    let statusHtml = '';
                                    switch (ticket.status) {
                                        case 'pending':
                                            statusHtml = '<span class="badge badge-pending">Pending</span>';
                                            break;
                                        case 'responded':
                                            statusHtml = '<span class="badge badge-responded">Responded</span>';
                                            break;
                                        case 'processed':
                                            statusHtml = '<span class="badge badge-processed">Processed</span>';
                                            break;
                                        case 'done':
                                            statusHtml = '<span class="badge badge-done">Done</span>';
                                            break;
                                        case 'rejected':
                                            statusHtml = '<span class="badge badge-rejected">Rejected</span>';
                                            break;
                                    }
                                    if (statusHtml && statusContainer.innerHTML !== statusHtml) {
                                        statusContainer.innerHTML = statusHtml;
                                    }
                                }
                            });

                            // 3. Mainkan suara jika total pesan belum dibaca bertambah
                            if (newUnreadTotal > currentUnreadTotal) {
                                playNotificationSound();
                            }
                            currentUnreadTotal = newUnreadTotal;
                        }
                    })
                    .catch(error => console.error('Error fetching unread data:', error));
            }

            function showNewTicketAlert() {
                // Play Sound untuk tiket baru
                playNotificationSound();

                // Hapus alert lama jika masih ada
                const oldAlert = document.getElementById('new-ticket-toast');
                if (oldAlert) oldAlert.remove();

                const alertHtml = `
                    <div id="new-ticket-toast" class="new-ticket-toast-container animate__animated animate__fadeInRight">
                        <div class="toast-content">
                            <div class="toast-icon">
                                <i class="fa fa-bell animate__animated animate__swing animate__infinite"></i>
                            </div>
                            <div class="toast-body">
                                <h6>Pesan Baru Masuk!</h6>
                                <p>Silakan refresh untuk melihat data terbaru.</p>
                                <a href="javascript:location.reload()" class="btn btn-primary btn-xs btn-refresh-now">
                                    <i class="fa fa-refresh"></i> Refresh Sekarang
                                </a>
                            </div>
                            <button type="button" class="btn-close-toast" onclick="this.parentElement.parentElement.remove()">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', alertHtml);
            }

            // Jalankan polling setiap 5 detik
            setInterval(fetchUnreadData, 5000);
        </script>
    @endpush
@endsection
