@extends('layouts.master')

@section('title')
    Data Broadcast
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
                        <h5 class="fw-bold mb-0">Data Broadcast</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('broadcasts.create') }}">
                            <i class="fa fa-plus me-1"></i> Create
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
                                        <th>Title</th>
                                        <th>Users</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($broadcasts as $broadcast)
                                        <tr>
                                            <td class="dt-col-no">{{ $loop->iteration }}</td>

                                            <td>{{ $broadcast->title }}</td>
                                            <td>
                                                @if ($broadcast->users->count())
                                                    {{ $broadcast->users->pluck('name')->join(', ') }}
                                                @else
                                                    <em>All Users</em>
                                                @endif
                                            </td>

                                            <td>{{ $broadcast->created_at->format('d/m/y H:i') }}</td>

                                            <td>
                                                <!-- Edit button -->
                                                <a href="" class="btn btn-success btn-sm">
                                                    Edit
                                                </a>

                                                <!-- Show button -->
                                                <a href="" class="btn btn-secondary btn-sm">
                                                    Show
                                                </a>

                                                <!-- Delete button -->
                                                <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-action="{{ route('broadcasts.destroy', $broadcast->id) }}"
                                                    data-name="{{ $broadcast->title }}">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No broadcast data</td>
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
                    <p>Are you sure you want to delete this broadcast <strong id="deleteItemName"></strong> ?</p>
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
