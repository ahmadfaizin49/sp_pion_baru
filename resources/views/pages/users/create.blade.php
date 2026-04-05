@extends('layouts.master')

@section('title')
    Buat Anggota
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Create -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Buat Anggota</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('users.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>


            <!-- Card Create -->
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

                        {{-- Form untuk create user --}}
                        <form method="POST" action="{{ route('users.store') }}" class="form theme-form">
                            @csrf

                            <!-- Input Name -->
                            <div class="mb-3">
                                <label>Nama</label>
                                <input class="form-control" type="text" name="name"
                                    value="{{ old('name') }}" required />
                            </div>

                            <!-- Input NIK KTP -->
                            <div class="mb-3">
                                <label>NIK KTP</label>
                                <input class="form-control" type="text" name="nik_ktp"
                                    value="{{ old('nik_ktp') }}" maxlength="20" pattern="\d*" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                            </div>

                            <!-- Input NIK Karyawan -->
                            <div class="mb-3">
                                <label>NIK Karyawan</label>
                                <input class="form-control" type="text" name="nik_karyawan"
                                    value="{{ old('nik_karyawan') }}" maxlength="20" pattern="\d*" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" required />
                            </div>

                            <!-- Input KTA -->
                            <div class="mb-3">
                                <label>KTA</label>
                                <input class="form-control" type="text" name="kta_number"
                                    value="{{ old('kta_number') }}" maxlength="15" pattern="\d*"
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    required />
                            </div>

                            <!-- Input Nomor Barcode -->
                            <div class="mb-3">
                                <label>Nomor Barcode</label>
                                <input class="form-control" type="text" name="barcode_number"
                                    value="{{ old('barcode_number') }}" maxlength="20" pattern="\d*"
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    required />
                            </div>

                            <!-- Input Departemen -->
                            <div class="mb-3">
                                <label>Departemen</label>
                                <input class="form-control" type="text" name="department"
                                    value="{{ old('department') }}" required />
                            </div>

                            <!-- Input Phone -->
                            <div class="mb-3">
                                <label>No Telepon / WA</label>
                                <input class="form-control" type="text" name="phone"
                                    value="{{ old('phone') }}" maxlength="15" pattern="\d*" inputmode="numeric"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                            </div>

                            <!-- Input Email -->
                            <div class="mb-3">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email"
                                    value="{{ old('email') }}" />
                            </div>

                            <!-- Input Tanggal Join -->
                            <div class="mb-3">
                                <label>Tanggal Join</label>
                                <div class="input-group">
                                    <input class="birth-datepicker form-control" type="text" name="joint_date"
                                        value="{{ old('joint_date') }}" autocomplete="off"
                                        placeholder="-- Pilih Tanggal Join --" style="cursor: pointer;" />
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>

                            <!-- Input Jenis Kelamin -->
                            <div class="mb-3">
                                <label>Jenis Kelamin</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                        Laki-Laki</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            <!-- Input Tempat Lahir -->
                            <div class="mb-3">
                                <label>Tempat Lahir</label>
                                <input class="form-control" type="text" name="birth_place"
                                    value="{{ old('birth_place') }}" />
                            </div>

                            <!-- Input Tanggal Lahir -->
                            <div class="mb-3">
                                <label>Tanggal Lahir</label>
                                <div class="input-group">
                                    <input class="birth-datepicker form-control" type="text" name="birth_date"
                                        value="{{ old('birth_date') }}" autocomplete="off"
                                        placeholder="-- Pilih Tanggal Lahir --" style="cursor: pointer;" />
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>


                            <!-- Input Agama -->
                            <div class="mb-3">
                                <label>Agama</label>
                                <select class="form-select" name="religion" required>
                                    <option value="">-- Pilih Agama --</option>
                                    <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>
                                        Islam</option>
                                    <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : '' }}>
                                        Kristen</option>
                                    <option value="Katolik" {{ old('religion') == 'Katolik' ? 'selected' : '' }}>
                                        Katolik</option>
                                    <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>
                                        Hindu</option>
                                    <option value="Buddha" {{ old('religion') == 'Buddha' ? 'selected' : '' }}>
                                        Buddha</option>
                                    <option value="Khonghucu"
                                        {{ old('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="Lainnya" {{ old('religion') == 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                            </div>

                            <!-- Input Pendidikan -->
                            <div class="mb-3">
                                <label>Pendidikan</label>
                                <select class="form-select" name="education" required>
                                    <option value="">-- Pilih Pendidikan --</option>
                                    <option value="SD" {{ old('education') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('education') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA/SMK" {{ old('education') == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                    <option value="D3" {{ old('education') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('education') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('education') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('education') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                            </div>

                            <!-- Input Alamat -->
                            <div class="mb-3">
                                <label>Alamat</label>
                                <textarea class="form-control" name="address" rows="3" required>{{ old('address') }}</textarea>
                            </div>

                            <!-- Input PIN -->
                            <div class="mb-3">
                                <label>PIN</label>
                                <input class="form-control" type="text" name="pin"
                                    value="{{ old('pin', '123456') }}" maxlength="6" pattern="\d*"
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    required />
                            </div>

                            <!-- Input Password -->
                            <div class="mb-3">
                                <label>Password</label>
                                <input class="form-control" type="text" name="password"
                                    value="{{ old('password', 'password123') }}" required />
                            </div>

                            <!-- Button Submit -->
                            <div class="row">
                                <div class="col">
                                    <div class="text-end">
                                        <button class="btn btn-success" type="submit">
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
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.id.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.birth-datepicker').datepicker({
                    language: 'id',
                    view: 'years',
                    minView: 'days',
                    dateFormat: 'dd/mm/yyyy',
                    autoClose: false,
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
                                '<button type="button" class="btn btn-light btn-sm">Batal</button>');
                            var $okBtn = $(
                                '<button type="button" class="btn btn-primary btn-sm">OK</button>');

                            $buttons.append($cancelBtn).append($okBtn);

                            $cancelBtn.on('click', function() {
                                dp.hide();
                            });

                            $okBtn.on('click', function() {
                                dp.hide();
                            });
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
