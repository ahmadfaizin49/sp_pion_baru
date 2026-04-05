@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <style>
        /* Local overrides if needed */
    </style>
@endpush


@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Create -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Dashboard</h5>
                    </div>
                </div>
            </div>

            <!-- Start Baris 1 -->
            {{-- Card 1 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="users"></i></div>
                            <div class="media-body">
                                <span class="m-0">Anggota</span>
                                <h4 class="mb-0 counter">{{ $totalUsers }}</h4>
                                <i class="icon-bg" data-feather="users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="edit"></i></div>
                            <div class="media-body">
                                <span class="m-0">Informasi</span>
                                <h4 class="mb-0 counter">{{ $totalInformations }}</h4>
                                <i class="icon-bg" data-feather="edit"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="layers"></i></div>
                            <div class="media-body">
                                <span class="m-0">Materi Belajar</span>
                                <h4 class="mb-0 counter">{{ $totalLearnings }}</h4>
                                <i class="icon-bg" data-feather="layers"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Baris 1 -->

            <!-- Start Baris 2 -->
            {{-- Card 4 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>
                            <div class="media-body">
                                <span class="m-0">Laporan Keuangan</span>
                                <h4 class="mb-0 counter">{{ $totalFinancials }}</h4>
                                <i class="icon-bg" data-feather="dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 5 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="globe"></i></div>
                            <div class="media-body">
                                <span class="m-0">Struktur Organisasi</span>
                                <h4 class="mb-0 counter">{{ $totalOrganizations }}</h4>
                                <i class="icon-bg" data-feather="globe"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 6 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="share-2"></i></div>
                            <div class="media-body">
                                <span class="m-0">Program Sosial</span>
                                <h4 class="mb-0 counter">{{ $totalSocials }}</h4>
                                <i class="icon-bg" data-feather="share-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Baris 2 -->

            <!-- Start Baris 3 -->
            {{-- Card 7 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="send"></i></div>
                            <div class="media-body">
                                <span class="m-0">Serikat SP PION</span>
                                <h4 class="mb-0 counter">{{ $totalUnions }}</h4>
                                <i class="icon-bg" data-feather="send"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 8 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="check-square"></i></div>
                            <div class="media-body">
                                <span class="m-0">Pemilu</span>
                                <h4 class="mb-0 counter">{{ $totalVotes }}</h4>
                                <i class="icon-bg" data-feather="check-square"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 9 --}}
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-premium-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="message-circle"></i></div>
                            <div class="media-body">
                                <span class="m-0">Pesan</span>
                                <h4 class="mb-0 counter">{{ $totalTickets }}</h4>
                                <i class="icon-bg" data-feather="message-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Baris 3 -->


            {{-- Table --}}
            @if ($recentMembers->isNotEmpty())
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pendaftaran Anggota Terbaru</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIK KTP</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Departemen</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentMembers as $member)
                                        <tr>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->nik_ktp }}</td>
                                            <td>
                                                @if ($member->gender == 'male')
                                                    <span class="badge badge-male">Laki-laki</span>
                                                @elseif($member->gender == 'female')
                                                    <span class="badge badge-female">Perempuan</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $member->department }}</td>
                                            <td>{{ $member->created_at->translatedFormat('d F Y') }}</td>
                                            <td>
                                                @if ($member->status == 'pending')
                                                    <span class="badge badge-pending">Menunggu Persetujuan</span>
                                                @elseif($member->status == 'approved')
                                                    <span class="badge badge-approved">Sudah Disetujui</span>
                                                @elseif($member->status == 'rejected')
                                                    <span class="badge badge-rejected">Ditolak</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($member->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('members.show', $member->id) }}"
                                                    class="btn btn-primary btn-xs">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
