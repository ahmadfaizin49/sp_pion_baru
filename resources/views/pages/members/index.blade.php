@extends('layouts.master')

@section('title')
    Data Registrasi Member
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
                        <h5 class="fw-bold mb-0">Data Registrasi Member</h5>
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

                        {{-- Table untuk list Member Registration --}}
                        @if ($members->count() > 0)
                            <div class="table-responsive">
                                <table class="display" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th class="dt-col-no">No</th>
                                            <th>Nama</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Status</th>
                                            <th>Pendaftar</th>
                                            <th>Tanggal Registrasi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $member)
                                            <tr>
                                                <td class="dt-col-no">{{ $loop->iteration }}</td>

                                                <td>{{ $member->name }}</td>

                                                <td>
                                                    @if ($member->gender == 'male')
                                                        <span class="badge badge-male">Laki-laki</span>
                                                    @elseif($member->gender == 'female')
                                                        <span class="badge badge-female">Perempuan</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($member->status == 'pending')
                                                        <span class="badge badge-pending">Menunggu Persetujuan</span>
                                                    @elseif($member->status == 'approved')
                                                        <span class="badge badge-approved">Sudah Disetujui</span>
                                                    @elseif($member->status == 'rejected')
                                                        <span class="badge badge-rejected">Ditolak</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($member->status) }}</span>
                                                    @endif
                                                </td>

                                                <td>{{ $member->referrer->name }}</td>

                                                <td>{{ $member->created_at->format('d/m/y H:i') }}</td>

                                                <td>
                                                    <!-- Edit button -->
                                                    <a href="{{ route('members.edit', $member->id) }}"
                                                        class="btn btn-success btn-xs">
                                                        Edit
                                                    </a>

                                                    <!-- Show button -->
                                                    <a href="{{ route('members.show', $member->id) }}"
                                                        class="btn btn-secondary btn-xs">
                                                        Lihat
                                                    </a>

                                                    <!-- Preview pdf button -->
                                                    <a href="{{ route('members.pdf', $member->id) }}" target="_blank"
                                                        class="btn btn-primary btn-xs">
                                                        Cetak PDF
                                                    </a>

                                                    <!-- Delete button -->
                                                    @if($member->status == 'approved' || $member->status == 'rejected')
                                                        <a href="#" class="btn btn-danger btn-xs"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal"
                                                            data-action="{{ route('members.destroy', $member->id) }}"
                                                            data-name="{{ $member->name }}">
                                                            Hapus
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center p-5">
                                <span class="text-muted">Tidak ada data registrasi member</span>
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
                    <p>Apakah Anda yakin ingin menghapus data member <strong id="deleteItemName"></strong>?</p>
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
    @endpush
@endsection
