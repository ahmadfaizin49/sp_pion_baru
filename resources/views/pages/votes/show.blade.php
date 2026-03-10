@extends('layouts.master')

@section('title')
    Detail Pemilu
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
                        <h5 class="fw-bold mb-0">Detail Pemilu</h5>
                        <a class="btn btn-primary" href="{{ route('votes.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="mb-3">
                            <label>Judul</label>
                            <div class="form-control-plaintext py-0">
                                {{ $vote->title }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Total Kandidat</label>
                            <div class="form-control-plaintext py-0">
                                @foreach ($vote->options as $option)
                                    {{ $loop->iteration }}. {{ $option->label }}<br>
                                @endforeach
                            </div>
                        </div>


                        <div class="mb-3">
                            <label>Status</label>
                            <div class="form-control-plaintext py-0">
                                @if ($vote->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Not Active</span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="fw-bold text-uppercase mb-3">Hasil Perolehan Suara</label>

                            @php
                                $totalVotesCount = $vote->options->sum('results_count');
                                $totalEligibleUsers = \App\Models\User::where('role', 'user')->count();
                                $participationRate =
                                    $totalEligibleUsers > 0
                                        ? round(($totalVotesCount / $totalEligibleUsers) * 100, 1)
                                        : 0;
                            @endphp

                            <div class="row">
                                @foreach ($vote->options as $option)
                                    @php
                                        $optionPercentage =
                                            $totalVotesCount > 0
                                                ? round(($option->results_count / $totalVotesCount) * 100, 1)
                                                : 0;
                                    @endphp
                                    <div class="col-md-12 mb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-bold">{{ $loop->iteration }}. {{ $option->label }}</span>
                                            <span class="fw-bold">
                                                {{ $option->results_count }} Suara ({{ $optionPercentage }}%)
                                            </span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $optionPercentage }}%"
                                                aria-valuenow="{{ $optionPercentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center px-2">
                                        <span class="fw-bold text-uppercase">TOTAL SUARA MASUK</span>
                                        <span class="fw-bold">{{ $participationRate }}%</span>
                                    </div>
                                    <div class="progress mt-1 mx-2">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $participationRate }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
