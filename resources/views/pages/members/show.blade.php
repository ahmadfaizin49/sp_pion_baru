@extends('layouts.master')

@section('title')
    Detail Registrasi Member
@endsection

@push('css')
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

            <!-- Card Detail -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Header -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Status</label>
                                    <div class="form-control-plaintext py-0">
                                        @if ($member->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                        @else
                                            <span class="badge bg-success text-dark">Sudah Disetujui</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Pendaftar</label>
                                    <div class="form-control-plaintext py-0">
                                        <span class="badge bg-primary"> {{ $member->referrer->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 1 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>NIK</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->nik }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Departemen</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->department }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>No. Telepon / WA</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 3 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Tempat Lahir</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->birth_place }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Tanggal Lahir</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->translatedFormat('j F Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Jenis Kelamin</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->gender == 'male' ? 'Laki-laki' : ($member->gender == 'female' ? 'Perempuan' : '-') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Agama</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->religion }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 4 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Pendidikan</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->education }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $member->address }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('members.pdf', $member->id) }}"
                                    class="btn btn-outline-danger" target="_blank">
                                    <i class="fa fa-file-pdf me-1"></i> Preview PDF
                                </a>

                                @if ($member->status == 'pending')
                                    <form action="{{ route('members.approve', $member->id) }}" method="POST"
                                        id="approveForm">
                                        @csrf
                                        <button type="button" class="btn btn-success" onclick="confirmApprove()">
                                            <i class="fa fa-check-circle me-1"></i> Setujui & Buat Akun
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-success" disabled>
                                        <i class="fa fa-check-double me-1"></i> Sudah Aktif
                                    </button>
                                @endif
                            </div>
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
                    text: "Member akan dibuatkan akun otomatis dengan password: password1234",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Approve!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('approveForm').submit();
                    }
                })
            }
        </script>
    @endpush
@endsection
