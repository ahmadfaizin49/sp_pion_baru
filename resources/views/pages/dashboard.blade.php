@extends('layouts.master')

@section('title')
    Dashboard
@endsection


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
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-primary b-r-4 card-body">
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
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-primary b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="align-self-center text-center"><i data-feather="user-plus"></i></div>
                            <div class="media-body">
                                <span class="m-0">Regis Member</span>
                                <h4 class="mb-0 counter">{{ $totalRegistrations }}</h4>
                                <i class="icon-bg" data-feather="user-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-primary b-r-4 card-body">
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

            {{-- Card 4 --}}
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-primary b-r-4 card-body">
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

            {{-- Card 1 --}}
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-info b-r-4 card-body">
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

            {{-- Card 2 --}}
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-info b-r-4 card-body">
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

            {{-- Card 3 --}}
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-info b-r-4 card-body">
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

            {{-- Card 4 --}}
            <div class="col-sm-6 col-xl-3 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-info b-r-4 card-body">
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

            <!-- End Baris 2 -->


            {{-- Table --}}
            <div class="col-xl-12 recent-order-sec">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <h5>Pendaftaran Anggota Terbaru</h5>
                            <table class="table table-bordernone">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Departemen</th>
                                        <th>Tanggal Daftar</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentMembers as $member)
                                        <tr>
                                            <td>
                                                <div class="media">
                                                    {{-- Placeholder jika tidak ada foto, atau pakai inisial --}}
                                                    <div class="media-body">
                                                        <a href="{{ route('members.show', $member->id) }}">
                                                            <span class="fw-bold">{{ $member->name }}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p>{{ $member->nik }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $member->department }}</p>
                                            </td>
                                            <td>
                                                <p>{{ $member->created_at->format('d M Y') }}</p>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $member->status == 'approved' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($member->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('members.show', $member->id) }}"
                                                    class="btn btn-xs btn-primary-light">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada pendaftaran member terbaru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
