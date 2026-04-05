@extends('layouts.master')

@section('title')
    Pengaturan Aplikasi
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
                        <h5 class="fw-bold mb-0">Pengaturan Aplikasi</h5>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-soft-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            @foreach ($settings as $key => $setting)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fw-semibold mb-0">{{ $setting->label }}</label>
                                    </div>

                                    @if ($setting->key === \App\Models\Setting::DASAR_HUKUM)
                                        {{-- Dynamic add/remove list for Dasar Hukum --}}
                                        @php
                                            $poinList = json_decode($setting->value, true) ?? [];
                                        @endphp
                                        <div id="dasarHukumList">
                                            @foreach($poinList as $i => $poin)
                                                <div class="d-flex align-items-center gap-2 mb-2 dasar-hukum-row">
                                                    <span class="badge bg-primary fs-6" style="width:32px; text-align:center; display:inline-block;">{{ $i + 1 }}</span>
                                                    <input type="text" class="form-control"
                                                        name="settings[dasar_hukum][]"
                                                        value="{{ $poin }}"
                                                        placeholder="Teks dasar hukum...">
                                                    @if($i >= 2)
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-poin flex-shrink-0"
                                                            onclick="removePoin(this)" title="Hapus">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted">
                                                <i class="fa fa-info-circle me-1"></i>
                                                Digunakan pada: <strong>PDF Surat Kuasa Member</strong>
                                            </small>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="addPoin()">
                                                <i class="fa fa-plus me-1"></i> Tambah Poin
                                            </button>
                                        </div>

                                    @elseif ($setting->key === \App\Models\Setting::KUASA_TEKS)
                                        <textarea class="form-control mb-1"
                                            name="settings[{{ $key }}]"
                                            rows="5"
                                            placeholder="Masukkan teks kuasa...">{{ $setting->value }}</textarea>

                                    @else
                                        <input type="text" class="form-control mb-1"
                                            name="settings[{{ $key }}]"
                                            value="{{ $setting->value }}"
                                            placeholder="Masukkan nilai...">
                                    @endif

                                    @if ($setting->key !== \App\Models\Setting::DASAR_HUKUM)
                                    <small class="text-muted">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Digunakan pada:
                                        <strong>
                                            @if ($setting->key === \App\Models\Setting::EMAIL_ORGANISASI)
                                                Header Kop PDF (Member & Pesan)
                                            @elseif ($setting->key === \App\Models\Setting::KUASA_TEKS)
                                                PDF Surat Kuasa Member
                                            @else
                                                Sistem
                                            @endif
                                        </strong>
                                    </small>
                                    @endif
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fa fa-save me-1"></i> Update
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function addPoin() {
                const list = document.getElementById('dasarHukumList');
                const rows = list.querySelectorAll('.dasar-hukum-row');
                const newIndex = rows.length + 1;
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center gap-2 mb-2 dasar-hukum-row';
                div.innerHTML = `
                    <span class="badge bg-primary fs-6" style="width:32px; text-align:center; display:inline-block;">${newIndex}</span>
                    <input type="text" class="form-control" name="settings[dasar_hukum][]"
                        placeholder="Teks dasar hukum...">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-poin flex-shrink-0"
                        onclick="removePoin(this)" title="Hapus">
                        <i class="fa fa-times"></i>
                    </button>
                `;
                list.appendChild(div);
                renumberPoin();
            }

            function removePoin(btn) {
                btn.closest('.dasar-hukum-row').remove();
                renumberPoin();
            }

            function renumberPoin() {
                const rows = document.querySelectorAll('#dasarHukumList .dasar-hukum-row');
                rows.forEach((row, i) => {
                    const badge = row.querySelector('.badge');
                    if (badge) badge.textContent = i + 1;
                });
            }
        </script>
    @endpush
@endsection
