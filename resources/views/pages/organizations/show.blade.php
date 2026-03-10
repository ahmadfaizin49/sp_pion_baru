@extends('layouts.master')

@section('title')
    Detail Struktur Organisasi
@endsection

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header -->
            <div class="col-md-12">
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Detail Struktur Organisasi</h5>
                        <a class="btn btn-primary" href="{{ route('organizations.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label>Judul</label>
                                <div class="form-control-plaintext py-0">
                                    {{ $organization->title }}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label>Deskripsi</label>
                                <div class="form-control-plaintext py-0">
                                    {{ $organization->description ?? '-' }}
                                </div>
                            </div>
                        </div>

                        {{-- Tampilkan Image jika ada --}}
                        @if ($organization->image_path)
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label>Foto</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $organization->image_path) }}"
                                            alt="{{ $organization->title }}" style="max-width: 400px; height: auto;">
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Optional: Embed PDF --}}
                        @if (Str::endsWith($organization->file_path, '.pdf'))
                            <iframe src="{{ asset('storage/' . $organization->file_path) }}"
                                style="width:100%; height:800px;" frameborder="0"></iframe>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
