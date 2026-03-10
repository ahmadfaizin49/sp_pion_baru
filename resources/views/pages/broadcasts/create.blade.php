@extends('layouts.master')

@section('title')
    Create Broadcast
@endsection

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Create -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Create Broadcast</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('broadcasts.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Back
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


                        <form method="POST" action="{{ route('broadcasts.store') }}">
                            @csrf

                            <!-- Input Title -->
                            <div class="mb-3">
                                <label>Title Broadcast</label>
                                <input class="form-control" type="text" name="title" value="{{ old('title') }}"
                                    required />
                            </div>

                            <!-- Input Body -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Body Broadcast</label>
                                        <textarea class="form-control" name="body" rows="3">{{ old('body') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Select User --}}
                            <div class="mb-3">
                                <label>Select User</label>
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
                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button class="btn btn-success" type="submit"
                                            onclick="this.disabled=true; this.form.submit();">
                                            <i class="fa fa-save me-1"></i> Submit
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
