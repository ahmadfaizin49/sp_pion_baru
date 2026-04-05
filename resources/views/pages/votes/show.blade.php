@extends('layouts.master')

@section('title')
    Detail Pemilu
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
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
                            <label>Daftar Kandidat</label>
                            <div class="form-control-plaintext py-0">
                                <div class="row">
                                    @foreach ($vote->options as $option)
                                        <div class="col-md-6 mb-2">
                                            <div class="p-2 border rounded">
                                                <span class="fw-bold">{{ $loop->iteration }}. {{ $option->label }}</span>
                                                <div
                                                    class="small text-muted mt-1 px-2 border-start border-3 border-primary">
                                                    <strong>Visi:</strong> {{ $option->vision ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label>Status</label>
                            <div class="form-control-plaintext py-0">
                                @if ($vote->is_active)
                                    <span class="badge badge-approved">Aktif</span>
                                @else
                                    <span class="badge badge-rejected">Tidak Aktif</span>
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

                            @foreach ($vote->options as $option)
                                @php
                                    $optionPercentage =
                                        $totalVotesCount > 0
                                            ? round(($option->results_count / $totalVotesCount) * 100, 1)
                                            : 0;
                                @endphp
                                <div class="card shadow-sm rounded-3 mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            @if ($option->user && $option->user->image_path)
                                                <img src="{{ asset('storage/' . $option->user->image_path) }}"
                                                    alt="{{ $option->label }}" class="rounded-circle me-3 shadow-sm"
                                                    style="width: 65px; height: 65px; object-fit: cover; border: 2px solid #AA2224;">
                                            @else
                                                <div class="rounded-circle me-3 shadow-sm d-flex align-items-center justify-content-center bg-light text-secondary"
                                                    style="width: 65px; height: 65px; border: 2px solid #AA2224;">
                                                    <i class="fa fa-user fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-bold">{{ $loop->iteration }}.
                                                        {{ $option->label }}</span>
                                                    <span class="fw-bold">
                                                        <span id="results-count-{{ $option->id }}">{{ $option->results_count }}</span> Suara 
                                                        (<span id="percentage-{{ $option->id }}">{{ $optionPercentage }}</span>%)
                                                    </span>
                                                </div>
                                                <div class="progress" style="height: 10px;">
                                                    <div id="progress-bar-{{ $option->id }}" class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $optionPercentage }}%"
                                                        aria-valuenow="{{ $optionPercentage }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="card shadow-sm rounded-3">
                            <div class="card-body p-3">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center px-2">
                                        <span class="fw-bold text-uppercase">TOTAL SUARA MASUK</span>
                                        <span class="fw-bold">
                                            <span id="total-votes-count">{{ $totalVotesCount }}</span> / <span id="total-eligible-users">{{ $totalEligibleUsers }}</span> Suara
                                            (<span id="total-participation-rate">{{ $participationRate }}</span>%)
                                        </span>
                                    </div>
                                    <div class="progress mt-1 mx-2">
                                        <div id="total-progress-bar" class="progress-bar bg-primary" role="progressbar"
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const voteId = {{ $vote->id }};

            // Polling hasil voting setiap 5 detik
            setInterval(function() {
                fetch(`/votes/${voteId}/results`)
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            const data = result.data;
                            
                            // Update individual options
                            data.options.forEach(option => {
                                const countElem = document.getElementById(`results-count-${option.id}`);
                                const percentElem = document.getElementById(`percentage-${option.id}`);
                                const progressElem = document.getElementById(`progress-bar-${option.id}`);

                                if (countElem) countElem.innerText = option.results_count;
                                if (percentElem) percentElem.innerText = option.percentage;
                                if (progressElem) {
                                    progressElem.style.width = option.percentage + '%';
                                    progressElem.setAttribute('aria-valuenow', option.percentage);
                                }
                            });

                            // Update total summary
                            const totalVotesElem = document.getElementById('total-votes-count');
                            const totalEligibleElem = document.getElementById('total-eligible-users');
                            const participationRateElem = document.getElementById('total-participation-rate');
                            const totalProgressElem = document.getElementById('total-progress-bar');

                            if (totalVotesElem) totalVotesElem.innerText = data.total_votes_count;
                            if (totalEligibleElem) totalEligibleElem.innerText = data.total_eligible_users;
                            if (participationRateElem) participationRateElem.innerText = data.participation_rate;
                            if (totalProgressElem) totalProgressElem.style.width = data.participation_rate + '%';
                        }
                    })
                    .catch(error => console.error('Error polling vote results:', error));
            }, 5000);
        });
    </script>
    @endpush
@endsection
