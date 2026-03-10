@extends('layouts.master')

@section('title')
    Edit Profile
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

                        {{-- Form untuk edit profile --}}
                        <form method="POST" action="{{ route('profile.update') }}" class="form theme-form">
                            @csrf
                            @method('PUT')


                            <!-- Baris 1 -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $user->name) }}" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Username</label>
                                        <input class="form-control" type="text" name="username"
                                            value="{{ old('username', $user->username) }}" required />
                                    </div>
                                </div>
                            </div>


                            <!-- Baris 2 -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <label>Password</label>
                                        <input class="form-control" type="text" name="password" />
                                        <small class="text-muted">
                                            Kosongkan jika tidak ingin mengubah password
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
