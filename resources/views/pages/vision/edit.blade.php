@extends('layouts.master')

@section('title')
    Edit Vision
@endsection

@push('css')
    <style>
        /* CKEditor wrapper */
        .cke {
            border: 1px solid #ced4da !important;
        }

        /* area dalam editor */
        .cke_contents {
            min-height: 150px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Edit -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Edit Visi Misi</h5>
                    </div>
                </div>
            </div>

            <!-- Card Edit -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body add-post">

                        {{-- Alert sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

                        {{-- Form untuk edit vision --}}
                        <form method="POST" action="{{ route('vision.update') }}" class="form theme-form">
                            @csrf
                            @method('PUT')

                            <!-- Input Vision -->
                            {{-- <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Vision</label>
                                        <input class="form-control" type="text" name="title"
                                            value="{{ old('title', $vision->title) }}" required />
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Input Mission -->
                            {{-- <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Mission</label>
                                        <input class="form-control" type="text" name="subtitle"
                                            value="{{ old('subtitle', $vision->subtitle) }}" required />
                                    </div>
                                </div>
                            </div> --}}

                            <!-- Input Vision -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Visi</label>
                                        <textarea id="vision-editor" name="title" cols="10" rows="2" required>{{ old('title', $vision->title) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Mission -->
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label>Misi</label>
                                        <textarea id="mision-editor" name="subtitle" cols="10" rows="2" required>{{ old('subtitle', $vision->subtitle) }}</textarea>
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
        <script src="{{ asset('assets/js/editor/ckeditor/ckeditor.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                CKEDITOR.replace('vision-editor');
                CKEDITOR.replace('mision-editor');
            });
        </script>
    @endpush
@endsection
