@extends('layouts.master')

@section('title')
    Buat Pemilu
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterange-picker.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Create -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Buat Pemilu</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('votes.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Create -->
            <div class="col-md-12">
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


                        <form method="POST" action="{{ route('votes.store') }}">
                            @csrf

                            <!-- Input Title -->
                            <div class="mb-3">
                                <label>Judul</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title') }}"
                                    required />
                            </div>

                            <!-- Input Description -->
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>


                            {{-- Select Candidates --}}
                            <div class="mb-4">
                                <label>Pilih Kandidat (Maksimal 8)</label>
                                <input type="text" id="candidateSearch" class="form-control mb-2"
                                    placeholder="Cari nama kandidat di sini...">

                                <div class="candidate-list-container p-3 border rounded"
                                    style="max-height: 250px; overflow-y: auto;">
                                    <div class="row m-0">
                                        @foreach ($users as $user)
                                            <div class="col-md-6 mb-2 candidate-item px-1">
                                                <div class="candidate-container p-2 border rounded">
                                                    <label class="d-flex align-items-center mb-0 cursor-pointer text-dark"
                                                        for="user_{{ $user->id }}">
                                                        <input class="checkbox_animated candidate-checkbox"
                                                            id="user_{{ $user->id }}" type="checkbox" name="options[]"
                                                            value="{{ $user->id }}" data-name="{{ $user->name }}"
                                                            {{ in_array($user->id, old('options', [])) ? 'checked' : '' }}>
                                                        <span
                                                            class="ms-2 candidate-name text-dark">{{ $user->name }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Dedicated Section for Visi Misi --}}
                            <div class="mb-4" id="visiMisiContainer" style="display: none;">
                                <hr class="my-4">
                                <label class="fw-bold fs-6 mb-3 text-primary">Visi & Misi Kandidat Terpilih</label>
                                <div class="row" id="visiMisiList">
                                    @foreach ($users as $user)
                                        <div class="col-md-6 mb-3 vision-wrapper" id="vision_wrapper_{{ $user->id }}"
                                            style="{{ in_array($user->id, old('options', [])) ? '' : 'display: none;' }}">
                                            <div class="p-3 border rounded shadow-sm bg-white">
                                                <label
                                                    class="fw-bold mb-2 text-dark border-bottom pb-2 d-block candidate-vision-label">
                                                    Visi Misi: {{ $user->name }}
                                                </label>
                                                <textarea class="form-control" name="visions[{{ $user->id }}]" rows="3"
                                                    placeholder="Ketik visi & misi untuk kandidat ini...">{{ old('visions.' . $user->id) }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Button Submit -->
                            <div class="text-end">
                                <button class="btn btn-success px-4" type="submit">
                                    <i class="fa fa-save me-1"></i> Submit Vote
                                </button>
                            </div>
                        </form>
                        {{-- End Form --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Search functionality
                const searchInput = document.getElementById('candidateSearch');
                const candidateItems = document.querySelectorAll('.candidate-item');

                if (searchInput) {
                    searchInput.addEventListener('keyup', function() {
                        const term = this.value.toLowerCase();
                        candidateItems.forEach(item => {
                            const name = item.querySelector('.candidate-name').textContent
                                .toLowerCase();
                            if (name.includes(term)) {
                                item.style.display = 'block';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                }

                // Selection & Visi Misi Separation Logic
                const checkboxes = document.querySelectorAll('.candidate-checkbox');
                const maxCandidates = 8;
                const visiMisiContainer = document.getElementById('visiMisiContainer');

                function updateVisibility() {
                    const checkedBoxes = document.querySelectorAll('.candidate-checkbox:checked');

                    if (checkedBoxes.length > maxCandidates) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Batas Maksimal',
                                text: 'Maksimal kandidat yang dapat dipilih adalah 8 orang.',
                                confirmButtonText: 'Mengerti',
                                confirmButtonColor: '#28a745'
                            });
                        } else {
                            alert('Maksimal kandidat yang dapat dipilih adalah 8 orang.');
                        }
                        return false;
                    }

                    if (checkedBoxes.length > 0) {
                        visiMisiContainer.style.display = 'block';
                    } else {
                        visiMisiContainer.style.display = 'none';
                    }

                    // Hide all vision wrappers first
                    document.querySelectorAll('.vision-wrapper').forEach(vw => {
                        vw.style.display = 'none';
                    });

                    // Show only selected and update their label index
                    checkedBoxes.forEach((cb, index) => {
                        const wrapper = document.getElementById(`vision_wrapper_${cb.value}`);
                        if (wrapper) {
                            wrapper.style.display = 'block';
                            const label = wrapper.querySelector('.candidate-vision-label');
                            label.innerHTML =
                                `<span class="badge bg-primary me-2">${index + 1}</span> ${cb.dataset.name}`;
                        }
                    });

                    return true;
                }

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const success = updateVisibility();
                        if (!success) {
                            this.checked = false; // rollback if exceeding max
                            updateVisibility();
                        }
                    });
                });

                // Initial call to handle old() inputs on page load
                updateVisibility();
            });
        </script>
        <style>
            .cursor-pointer {
                cursor: pointer;
            }

            .candidate-container:hover {
                background-color: #f8f9fa;
            }
        </style>
    @endpush
@endsection
