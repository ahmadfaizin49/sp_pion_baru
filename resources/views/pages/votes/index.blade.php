@extends('layouts.master')

@section('title')
    Data Pemilu
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Create -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Data Pemilu</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('votes.create') }}">
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
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Table untuk list votes --}}
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th class="dt-col-no">No</th>
                                        <th>Judul</th>
                                        <th>Total Kandidat</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($votes as $vote)
                                        <tr>
                                            <td class="dt-col-no">{{ $loop->iteration }}</td>

                                            <td>{{ $vote->title }}</td>

                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $vote->options_count }} KANDIDAT
                                                </span>
                                            </td>


                                            <td>
                                                @if ($vote->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Not Active</span>
                                                @endif
                                            </td>

                                            <td>{{ $vote->created_at->format('d/m/y H:i') }}</td>

                                            <td>
                                                <!-- Edit button -->
                                                <a href="{{ route('votes.edit', $vote->id) }}"
                                                    class="btn btn-success btn-xs">
                                                    Edit
                                                </a>

                                                <!-- Show button -->
                                                <a href="{{ route('votes.show', $vote->id) }}"
                                                    class="btn btn-secondary btn-xs">
                                                    Show
                                                </a>

                                                <!-- Delete button -->
                                                <a href="#" class="btn btn-danger btn-xs" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-action="{{ route('votes.destroy', $vote->id) }}"
                                                    data-name="{{ $vote->title }}">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No vote data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this vote <strong id="deleteItemName"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-danger" type="submit">Delete</button>
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
