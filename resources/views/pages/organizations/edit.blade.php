@extends('layouts.master')

@section('title')
    Edit Struktur Organisasi
@endsection

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Edit -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Edit Struktur Organisasi</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('organizations.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Edit -->
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

                        {{-- Alert Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Form untuk edit learning --}}
                        <form method="POST" action="{{ route('organizations.update', $organization->id) }}"
                            enctype="multipart/form-data" class="form theme-form">
                            @csrf
                            @method('PUT')

                            <!-- Input Title -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Judul</label>
                                        <input class="form-control" type="text" name="title"
                                            value="{{ old('title', $organization->title) }}" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Input Description -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Deskripsi</label>
                                        <textarea class="form-control" name="description" rows="3">{{ old('description', $organization->description) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Image -->
                            <div class="row">
                                <div class="col">
                                    @if ($organization->image_path)
                                        <div class="mb-3">
                                            <label>Foto Sekarang</label>
                                            <p>
                                                <img src="{{ asset('storage/' . $organization->image_path) }}"
                                                    alt="Current Image" style="max-width: 200px; height: auto;">
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Choose New Image -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Pilih Foto Baru (opsional)</label>
                                        <input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>


                            <!-- Current File -->
                            <div class="row">
                                <div class="col">
                                    @if ($organization->file_path)
                                        <div class="mb-3">
                                            <label>File Sekarang</label>
                                            <p>
                                                <a href="{{ asset('storage/' . $organization->file_path) }}"
                                                    target="_blank">
                                                    Download
                                                </a>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Choose New File -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Pilih File Baru (opsional)</label>
                                        <input class="form-control" type="file" name="file" accept=".pdf,.doc,.docx">
                                    </div>
                                </div>
                            </div>

                            <!-- Button Update -->
                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button class="btn btn-success" type="submit">
                                            <i class="fa fa-save me-1"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        {{-- End Form --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
