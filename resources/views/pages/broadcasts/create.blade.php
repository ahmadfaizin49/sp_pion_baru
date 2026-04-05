@extends('layouts.master')

@section('title')
    Create Broadcast
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
                        <h5 class="fw-bold mb-0">Tambah Broadcast</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('broadcasts.index') }}">
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


                        <form method="POST" action="{{ route('broadcasts.store') }}">
                            @csrf

                            <!-- Input Title -->
                            <div class="mb-3">
                                <label>Judul Broadcast</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title') }}"
                                    required />
                            </div>

                            <!-- Input Body -->
                            <div class="mb-3">
                                <label>Isi Broadcast</label>
                                <textarea class="form-control" name="body" rows="3">{{ old('body') }}</textarea>
                            </div>

                            {{-- Select User --}}
                            <div class="mb-3">
                                <label>Pilih User</label>
                                <div class="row">
                                    @foreach ($users as $user)
                                        <div class="col-md-6">
                                            <label class="d-block" for="user_{{ $user->id }}">
                                                <input class="checkbox_animated" id="user_{{ $user->id }}"
                                                    type="checkbox" name="users[]" value="{{ $user->id }}"
                                                    {{ in_array($user->id, old('users', [])) ? 'checked' : '' }}>
                                                {{ $user->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Button Submit -->
                            <div class="text-end">
                                <button class="btn btn-success" type="submit"
                                    onclick="this.disabled=true; this.form.submit();">
                                    <i class="fa fa-save me-1"></i> Kirim
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
