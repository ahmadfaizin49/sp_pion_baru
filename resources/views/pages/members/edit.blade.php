@extends('layouts.master')

@section('title')
    Edit Registrasi Member
@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Edit Registrasi Member</h5>
                        <a class="btn btn-primary" href="{{ route('members.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
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

                        {{-- Form untuk edit registrasi --}}
                        <form method="POST" action="{{ route('members.update', $member->id) }}" class="form theme-form">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label>Nama</label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ old('name', $member->name) }}" required />
                            </div>

                            <div class="mb-3">
                                <label>NIK KTP</label>
                                <input class="form-control" type="text" name="nik_ktp"
                                    value="{{ old('nik_ktp', $member->nik_ktp) }}" required />
                            </div>

                            <div class="mb-3">
                                <label>NIK Karyawan</label>
                                <input class="form-control" type="text" name="nik_karyawan"
                                    value="{{ old('nik_karyawan', $member->nik_karyawan) }}" required />
                            </div>

                            <div class="mb-3">
                                <label>Departemen</label>
                                <input class="form-control" type="text" name="department"
                                    value="{{ old('department', $member->department) }}" required />
                            </div>

                            <div class="mb-3">
                                <label>No Telepon / WA</label>
                                <input class="form-control" type="text" name="phone"
                                    value="{{ old('phone', $member->phone) }}" required />
                            </div>

                            <div class="mb-3">
                                <label>Tempat Lahir</label>
                                <input class="form-control" type="text" name="birth_place"
                                    value="{{ old('birth_place', $member->birth_place) }}" required />
                            </div>

                            <div class="mb-3">
                                <label>Tanggal Lahir</label>
                                <div class="input-group">
                                    <input class="birth-datepicker form-control" type="text" name="birth_date"
                                        value="{{ old('birth_date', $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('d/m/Y') : '') }}"
                                        autocomplete="off" required placeholder="-- Pilih Tanggal Lahir --"
                                        style="cursor: pointer;" />
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="male"
                                        {{ old('gender', $member->gender) == 'male' ? 'selected' : '' }}>
                                        Laki-Laki</option>
                                    <option value="female"
                                        {{ old('gender', $member->gender) == 'female' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Agama</label>
                                <select class="form-select" name="religion" required>
                                    <option value="">-- Pilih Agama --</option>
                                    @php
                                        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
                                    @endphp
                                    @foreach ($religions as $item)
                                        <option value="{{ $item }}"
                                            {{ old('religion', $member->religion) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Pendidikan</label>
                                <select class="form-select" name="education" required>
                                    <option value="">-- Pilih Pendidikan --</option>
                                    @php
                                        $educations = ['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2', 'S3'];
                                    @endphp
                                    @foreach ($educations as $item)
                                        <option value="{{ $item }}"
                                            {{ old('education', $member->education) == $item ? 'selected' : '' }}>
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ old('address', $member->address) }}</textarea>
                            </div>

                            <!-- Button Update -->
                            <div class="text-end mt-3">
                                <button class="btn btn-success" type="submit">
                                    <i class="fa fa-save me-1"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.id.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.birth-datepicker').each(function() {
                var $el = $(this);
                var val = $el.val();
                var initDate = new Date();
                var hasValue = false;

                if (val) {
                    var parts = val.split('/');
                    initDate = new Date(parts[2], parts[1] - 1, parts[0]);
                    hasValue = true;
                }

                var dp = $el.datepicker({
                    language: 'id',
                    view: 'days',
                    minView: 'days',
                    dateFormat: 'dd/mm/yyyy',
                    autoClose: false,
                    startDate: initDate,
                    onShow: function(dp, animationCompleted) {
                        if (!animationCompleted) {
                            var $buttons = dp.$datepicker.find('.datepicker--buttons');
                            if (!$buttons.length) {
                                dp.$datepicker.append(
                                    '<div class="datepicker--buttons" style="padding: 10px; border-top: 1px solid #efefef; display: flex; justify-content: center; gap: 5px;"></div>'
                                );
                                $buttons = dp.$datepicker.find('.datepicker--buttons');
                            }
                            $buttons.empty();

                            var $cancelBtn = $(
                                '<button type="button" class="btn btn-light btn-sm">Batal</button>'
                            );
                            var $okBtn = $(
                                '<button type="button" class="btn btn-primary btn-sm">OK</button>'
                            );

                            $buttons.append($cancelBtn).append($okBtn);

                            $cancelBtn.on('click', function() {
                                dp.hide();
                            });

                            $okBtn.on('click', function() {
                                dp.hide();
                            });
                        }
                    }
                }).data('datepicker');

                if (hasValue) {
                    dp.selectDate(initDate);
                }
            });
        });
    </script>
@endpush
