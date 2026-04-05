@extends('layouts.master')

@section('title')
    Buat Program Sosial
@endsection

@push('css')
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
                        <h5 class="fw-bold mb-0">Buat Program Sosial</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('socials.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Create -->
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


                        {{-- Form untuk create social --}}
                        <form method="POST" action="{{ route('socials.store') }}" enctype="multipart/form-data"
                            class="form theme-form">
                            @csrf

                            <!-- Input Title -->
                            <div class="mb-3">
                                <label>Judul</label>
                                <input class="form-control" type="text" name="title"
                                    value="{{ old('title') }}" required />
                            </div>

                            <!-- Input Description -->
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <!-- Choose Image -->
                            <div class="mb-3">
                                <label>Pilih Foto</label>
                                <input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png">
                            </div>

                            <!-- Choose File -->
                            <div class="mb-3">
                                <label>Pilih File</label>
                                <input class="form-control" type="file" name="file" accept=".pdf,.doc,.docx">
                            </div>

                            <!-- Button Submit -->
                            <div class="text-end">
                                <button class="btn btn-success" type="submit">
                                    <i class="fa fa-save me-1"></i> Submit
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
    @endpush
@endsection
