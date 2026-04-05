@extends('layouts.master')

@section('title')
    Data Anggota
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <style>
        .table-responsive table {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }


        /* Custom Soft UI Badges */
        .badge-male {
            background-color: #e7f1ff !important;
            color: #0052cc !important;
            border: 1px solid #cfe2ff;
            border-radius: 4px;
            font-weight: 600;
        }

        .badge-female {
            background-color: #fff0f3 !important;
            color: #af003d !important;
            border: 1px solid #f8d7da;
            border-radius: 4px;
            font-weight: 600;
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
                        <h5 class="fw-bold mb-0">Data Anggota</h5>

                        {{-- Tombol di kanan --}}
                        <div class="d-flex gap-2">
                            <a class="btn btn-info text-white" href="{{ route('users.template') }}">
                                <i class="fa fa-download me-1"></i> Template
                            </a>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fa fa-upload me-1"></i> Import
                            </button>
                            <a class="btn btn-warning text-white" href="{{ route('users.export') }}">
                                <i class="fa fa-file-excel-o me-1"></i> Export
                            </a>
                            <a class="btn btn-primary" href="{{ route('users.create') }}">
                                <i class="fa fa-plus me-1"></i> Buat
                            </a>
                        </div>
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

                        {{-- Alert Error (General) --}}
                        @if (session('error'))
                            <div class="alert alert-soft-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Alert Error HTML (For Excel Validation) --}}
                        @if (session('error_html'))
                            <div class="alert alert-soft-danger alert-dismissible fade show" role="alert">
                                {!! session('error_html') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Alert Error --}}
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

                        {{-- Table untuk list information --}}
                        @if ($users->count() > 0)
                            <div class="table-responsive">
                                <table class="display" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th class="dt-col-no">No</th>
                                            <th>Nama</th>
                                            <th>NIK KTP</th>
                                            <th>NIK Karyawan</th>
                                            <th>KTA</th>
                                            <th>Departemen</th>
                                            <th>Tanggal Join</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tempat Lahir</th>
                                            <th>Tanggal Lahir</th>
                                            <th>No Telepon</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="dt-col-no">{{ $loop->iteration }}</td>

                                                <td>{{ $user->name }}</td>

                                                <td>{{ $user->nik_ktp ?? '-' }}</td>
                                                <td>{{ $user->nik_karyawan }}</td>
                                                <td>{{ $user->kta_number ?? '-' }}</td>
                                                <td>{{ $user->department }}</td>
                                                <td>{{ $user->joint_date ? \Carbon\Carbon::parse($user->joint_date)->format('d-m-Y') : '-' }}
                                                <td>
                                                    @if ($user->gender == 'male')
                                                        <span class="badge badge-male">Laki-laki</span>
                                                    @elseif($user->gender == 'female')
                                                        <span class="badge badge-female">Perempuan</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $user->birth_place ?? '-' }}</td>
                                                <td>{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d-m-Y') : '-' }}
                                                </td>

                                                <td>{{ $user->phone ?? '-' }}</td>

                                                <td>
                                                    <!-- Edit button -->
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-success btn-xs">
                                                        Edit
                                                    </a>

                                                    <!-- Show button -->
                                                    <a href="{{ route('users.show', $user->id) }}"
                                                        class="btn btn-secondary btn-xs">
                                                        Lihat
                                                    </a>

                                                    <!-- Delete button -->
                                                    <a href="#" class="btn btn-danger btn-xs" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-action="{{ route('users.destroy', $user->id) }}"
                                                        data-name="{{ $user->name }}">
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
                                <span class="text-muted">Tidak ada data anggota</span>
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
                    <p>Apakah Anda yakin ingin menghapus data anggota <strong id="deleteItemName"></strong>?</p>
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

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Impor Data Anggota</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Pilih File Excel (.xlsx, .xls)</label>
                            <input type="file" name="file" class="form-control" required accept=".xlsx, .xls">
                            <small class="text-muted mt-2 d-block">
                                * Gunakan template yang sudah disediakan untuk menghindari kesalahan data.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Tutup</button>
                        <button class="btn btn-primary" type="submit">Unggah & Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Import --}}



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


    @endpush
@endsection
