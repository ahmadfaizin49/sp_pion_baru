@extends('layouts.master')

@section('title')
    Profil Saya
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush

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
                        <h5 class="fw-bold mb-0">Edit Profile</h5>

                    </div>
                </div>
            </div>


            <!-- Card Edit -->
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

                        {{-- Form untuk edit profile --}}
                        <form method="POST" action="{{ route('profile.update') }}" class="form theme-form"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            <!-- Name -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Nama</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $user->name) }}" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Username</label>
                                        <input class="form-control" type="text" name="username"
                                            value="{{ old('username', $user->username) }}" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Foto</label>
                                        <input class="form-control @error('image_path') is-invalid @enderror" type="file"
                                            name="image_path" accept="image/*">

                                        <div class="d-flex align-items-center mt-2">
                                            <small class="text-muted">
                                                @if ($user->image_path)
                                                    <span class="text-success"><i class="fa fa-check-circle"></i>
                                                        Terunggah:</span>
                                                    <span class="fw-medium">{{ basename($user->image_path) }}</span>
                                                @else
                                                    <span class="text-muted"><i>Belum ada file yang dipilih</i></span>
                                                @endif
                                            </small>
                                        </div>
                                        <div>
                                            <small class="text-muted">Format: <b>JPG, PNG, JPEG</b> (Maks: 5MB)</small>
                                        </div>

                                        @error('image_path')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Password</label>
                                        <input class="form-control" type="text" name="password"
                                            value="{{ old('password', $user->password_hint) }}" />
                                        <small class="text-muted">
                                            Password saat ini: <strong class="text-danger">{{ $user->password_hint ?? '-' }}</strong>
                                        </small>
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
