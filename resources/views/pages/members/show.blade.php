@extends('layouts.master')

@section('title')
    Detail Registrasi Member
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 15px;
            color: #212529;
            font-weight: 500;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #AA2224;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-md-12">
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Detail Registrasi Member</h5>
                        <a class="btn btn-primary" href="{{ route('members.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Left Column: Status & System Info -->
                            <div class="col-md-4 border-end">
                                <div class="section-title">
                                    Status & Sistem
                                </div>

                                <div class="mb-4">
                                    <p class="detail-label">Status Pendaftaran</p>
                                    @if ($member->status == 'pending')
                                        <span class="badge badge-pending">Menunggu Persetujuan</span>
                                    @elseif($member->status == 'approved')
                                        <span class="badge badge-approved">Sudah Disetujui</span>
                                    @elseif($member->status == 'rejected')
                                        <span class="badge badge-rejected">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($member->status) }}</span>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <p class="detail-label">Pendaftar</p>
                                    <div class="d-flex align-items-center">
                                        <p class="detail-value text-primary fw-bold mb-0">{{ $member->referrer->name }}</p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <p class="detail-label">Tanggal Registrasi</p>
                                    <p class="detail-value text-dark fw-bold">
                                        {{ $member->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Right Column: Personal Data -->
                            <div class="col-md-8 ps-md-4">
                                <div class="section-title">
                                    Informasi Pribadi
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">Nama Lengkap</p>
                                        <p class="detail-value text-dark fw-bold">{{ $member->name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">NIK Karyawan</p>
                                        <p class="detail-value">{{ $member->nik_karyawan }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">NIK KTP</p>
                                        <p class="detail-value">{{ $member->nik_ktp }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">No Telepon / WA</p>
                                        <p class="detail-value">{{ $member->phone ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">Departemen</p>
                                        <p class="detail-value">{{ $member->department }}</p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">Tempat, Tanggal Lahir</p>
                                        <p class="detail-value">
                                            {{ $member->birth_place }},
                                            {{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->translatedFormat('j F Y') : '-' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">Jenis Kelamin</p>
                                        <p class="detail-value">
                                            @if ($member->gender == 'male')
                                                <span class="badge badge-male">Laki-laki</span>
                                            @elseif($member->gender == 'female')
                                                <span class="badge badge-female">Perempuan</span>
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <p class="detail-label">Agama | Pendidikan</p>
                                        <p class="detail-value">{{ $member->religion }} | {{ $member->education }}</p>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <p class="detail-label">Alamat Lengkap</p>
                                        <p class="detail-value">{{ $member->address }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2 mt-2">
                            <a href="{{ route('members.pdf', $member->id) }}" class="btn btn-primary px-4"
                                target="_blank">
                                <i class="fa fa-file-text me-2"></i> Lihat PDF
                            </a>

                            @if ($member->status == 'pending')
                                <form action="{{ route('members.reject', $member->id) }}" method="POST" id="rejectForm">
                                    @csrf
                                    <button type="button" class="btn btn-danger px-4" onclick="confirmReject()">
                                        <i class="fa fa-times me-2"></i> Tolak
                                    </button>
                                </form>

                                <form action="{{ route('members.approve', $member->id) }}" method="POST" id="approveForm">
                                    @csrf
                                    <button type="button" class="btn btn-success px-4" onclick="confirmApprove()">
                                        <i class="fa fa-check me-2"></i> Setujui
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-info px-4" disabled>
                                    <i class="fa fa-info-circle me-2"></i> Status : {{ ucfirst($member->status) }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmApprove() {
                Swal.fire({
                    title: 'Konfirmasi Persetujuan',
                    html: "Member akan dibuatkan akun otomatis dengan <br> Password: <strong class='text-danger'>password123</strong> dan PIN: <strong class='text-danger'>123456</strong>",
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Setuju!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('approveForm').submit();
                    }
                })
            }

            function confirmReject() {
                Swal.fire({
                    title: 'Konfirmasi Penolakan',
                    text: 'Apakah Anda yakin ingin menolak pendaftaran member ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('rejectForm').submit();
                    }
                })
            }
        </script>
    @endpush
@endsection
