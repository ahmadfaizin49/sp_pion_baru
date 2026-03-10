@extends('layouts.master')

@section('title')
    Detail Anggota
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

                        <!-- Baris 1 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>NIK</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->nik }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>KTA</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->kta_number ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Departemen</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->department }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris 2 -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>No. Telepon / WA</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Email</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->email ?? '-' }}
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
                                        {{ $user->birth_place ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Tanggal Lahir</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->translatedFormat('j F Y') : '-' }}
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
                                        {{ $user->gender == 'male' ? 'Laki-laki' : ($user->gender == 'female' ? 'Perempuan' : '-') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Agama</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->religion }}
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
                                        {{ $user->education ?? '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Alamat</label>
                                    <div class="form-control-plaintext py-0">
                                        {{ $user->address }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <label>Barcode</label>
                                    <div class="form-control-plaintext py-0">
                                        @if ($user->barcode_number)
                                            {{-- Ini akan meng-generate Barcode tipe DNS1D (Standard Barcode) --}}
                                            {!! DNS1D::getBarcodeHTML($user->barcode_number, 'C128', 2, 50) !!}
                                        @else
                                            -
                                        @endif
                                    </div>
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
