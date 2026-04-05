@extends('layouts.master')

@section('title')
    Detail Anggota
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-md-12">
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Detail Anggota</h5>
                        <a class="btn btn-primary" href="{{ route('users.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Personal Information -->
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->name }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>NIK KTP</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->nik_ktp ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>NIK Karyawan</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->nik_karyawan }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>KTA</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->kta_number ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Departemen</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->department }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>No Telepon / WA</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->phone ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Email</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->email ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Tanggal Join</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->joint_date ? \Carbon\Carbon::parse($user->joint_date)->translatedFormat('j F Y') : '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Tempat, Tanggal Lahir</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->birth_place ?? '-' }},
                                {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('j F Y') : '-' }}
                            </div>
                        </div>


                        <div class="mb-3">
                            <label>Jenis Kelamin</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->gender == 'male' ? 'Laki-laki' : ($user->gender == 'female' ? 'Perempuan' : '-') }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Agama</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->religion }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Pendidikan</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->education ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
                            <div class="form-control-plaintext py-0">
                                {{ $user->address }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Barcode</label>
                            <div class="form-control-plaintext py-0">
                                @if ($user->barcode_number)
                                    {!! DNS1D::getBarcodeHTML($user->barcode_number, 'C128', 2, 50) !!}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <hr>
                        {{-- Kartu Tanda Anggota --}}
                        <div class="mb-3">
                            <label class="fw-bold text-uppercase mb-2 d-block">Kartu Tanda Anggota (KTA)</label>
                            <div class="d-flex gap-2">
                                <a href="{{ route('users.kta', $user->id) }}"
                                   target="_blank"
                                   class="btn btn-primary btn-sm">
                                    <i class="fa fa-eye me-1"></i> Preview KTA
                                </a>
                                <a href="{{ route('users.kta', $user->id) }}?mode=download"
                                   class="btn btn-success btn-sm">
                                    <i class="fa fa-download me-1"></i> Download KTA
                                </a>
                            </div>
                        </div>

                        <hr>
                        <div class="mb-2">
                            <label class="fw-bold text-uppercase mb-3">Informasi Keamanan</label>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="d-block">PIN</label>
                                    <span class="badge badge-rejected" style="font-size: 14px;">
                                        {{ $user->pin_hint ?? '-' }}
                                    </span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="d-block">Password</label>
                                    <span class="badge badge-rejected" style="font-size: 14px;">
                                        {{ $user->password_hint ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
