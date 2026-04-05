@extends('layouts.master')

@section('title')
    Edit Pemilu
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterange-picker.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-md-12">
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Edit Pemilu</h5>
                        <a class="btn btn-primary" href="{{ route('votes.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Edit -->
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

                        {{-- Form untuk edit vote --}}
                        <form method="POST" action="{{ route('votes.update', $vote->id) }}">
                            @csrf
                            @method('PUT')


                            <!-- Title -->
                            <div class="mb-3">
                                <label>Judul</label>
                                <input class="form-control" type="text" name="title"
                                    value="{{ old('title', $vote->title) }}" required />
                            </div>

                            <!-- Input Description -->
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description', $vote->description) }}</textarea>
                            </div>

                            <!-- Candidates -->
                            <div class="mb-3">
                                <label>Kandidat & Visi</label>
                                <div class="row">
                                    @foreach ($vote->options as $option)
                                        <div class="col-md-6 mb-3">
                                            <div class="p-2 border rounded">
                                                <span class="fw-bold d-block mb-1">{{ $loop->iteration }}. {{ $option->label }}</span>
                                                <div class="vision-field pt-1 border-top">
                                                    <label class="small text-muted mb-1">Visi Misi</label>
                                                    <textarea class="form-control form-control-sm" name="visions[{{ $option->id }}]" rows="2" placeholder="Masukkan visi misi...">{{ old('visions.' . $option->id, $option->vision) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label>Status</label>
                                <div class="media-body">
                                    <label class="switch">
                                        <!-- hidden input supaya selalu ada value -->
                                        <input type="hidden" name="is_active" value="0">

                                        <!-- checkbox toggle -->
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ $vote->is_active ? 'checked' : '' }}>

                                        <span class="switch-state"></span>
                                    </label>
                                </div>
                            </div>

                            <!-- Button Update -->
                            <div class="text-end">
                                <button class="btn btn-success" type="submit">
                                    <i class="fa fa-save me-1"></i> Update
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
