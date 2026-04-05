@extends('layouts.master')

@section('title')
    Data Program Sosial
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
                        <h5 class="fw-bold mb-0">Data Program Sosial</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('socials.create') }}">
                            <i class="fa fa-plus me-1"></i> Buat
                        </a>
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

                        {{-- Table untuk list social --}}
                        @if ($socials->count() > 0)
                            <div class="table-responsive">
                                <table class="display" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th class="dt-col-no">No</th>
                                            <th>Judul</th>
                                            <th>Foto</th>
                                            <th>File</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($socials as $social)
                                            <tr>
                                                <td class="dt-col-no">{{ $loop->iteration }}</td>

                                                <td>{{ $social->title }}</td>

                                                <td>
                                                    @if ($social->image_path)
                                                        <a href="{{ asset('storage/' . $social->image_path) }}"
                                                            target="_blank" class="btn-premium btn-premium-info">
                                                            <i class="fa fa-eye"></i> Lihat Foto
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)" class="btn-premium btn-premium-light disabled">
                                                            <i class="fa fa-times"></i> Tidak Ada Foto
                                                        </a>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($social->file_path)
                                                        <a href="{{ asset('storage/' . $social->file_path) }}"
                                                            target="_blank" class="btn-premium btn-premium-success">
                                                            <i class="fa fa-eye"></i> Lihat File
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)" class="btn-premium btn-premium-light disabled">
                                                            <i class="fa fa-times"></i> Tidak Ada File
                                                        </a>
                                                    @endif
                                                </td>

                                                <td>{{ $social->created_at->format('d/m/y H:i') }}</td>

                                                <td>
                                                    <!-- Edit button -->
                                                    <a href="{{ route('socials.edit', $social->id) }}"
                                                        class="btn btn-success btn-xs">
                                                        Edit
                                                    </a>

                                                    <!-- Show button -->
                                                    <a href="{{ route('socials.show', $social->id) }}"
                                                        class="btn btn-secondary btn-xs">
                                                        Lihat
                                                    </a>

                                                    <!-- Delete button -->
                                                    <a href="#" class="btn btn-danger btn-xs" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal"
                                                        data-action="{{ route('socials.destroy', $social) }}"
                                                        data-name="{{ $social->title }}">
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
                                <span class="text-muted">Tidak ada data program sosial</span>
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
                    <p>Apakah Anda yakin ingin menghapus data program sosial <strong id="deleteItemName"></strong>?</p>
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
