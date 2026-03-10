@extends('layouts.master')

@section('title')
    Data Registrasi Member
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
                        <h5 class="fw-bold mb-0">Data Registrasi Member</h5>
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

                        {{-- Table untuk list Member Registration --}}
                        <div class="table-responsive">
                            <table class="display" id="basic-1">
                                <thead>
                                    <tr>
                                        <th class="dt-col-no">No</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Pendaftar</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($members as $member)
                                        <tr>
                                            <td class="dt-col-no">{{ $loop->iteration }}</td>

                                            <td>{{ $member->name }}</td>

                                            <td>
                                                @if ($member->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                                @else
                                                    <span class="badge bg-success text-dark">Sudah Disetujui</span>
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
                                                    Show
                                                </a>

                                                <!-- Preview pdf button -->
                                                <a href="{{ route('members.pdf', $member->id) }}" target="_blank"
                                                    class="btn btn-primary btn-xs">
                                                    Print PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted p-4">No ticket data found</td>
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
                    <p>Are you sure you want to delete this ticket <strong id="deleteItemName"></strong> ?</p>
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
