@extends('layouts.master')

@section('title')
    Edit Anggota
@endsection

@push('css')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Header Edit -->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- Teks di kiri --}}
                        <h5 class="fw-bold mb-0">Edit Anggota</h5>

                        {{-- Tombol di kanan --}}
                        <a class="btn btn-primary" href="{{ route('users.index') }}">
                            <i class="fa fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>


            <!-- Card Edit -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Alert sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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

                        {{-- Form untuk edit user --}}
                        <form method="POST" action="{{ route('users.update', $user->id) }}" class="form theme-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Input Name -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $user->name) }}" required />
                                    </div>
                                </div>

                                <!-- Input NIK -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIK</label>
                                        <input class="form-control" type="text" name="nik"
                                            value="{{ old('nik', $user->nik) }}" maxlength="20" pattern="\d*"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            required />
                                    </div>
                                </div>

                                <!-- Input KTA -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>KTA</label>
                                        <input class="form-control" type="text" name="kta_number"
                                            value="{{ old('kta_number', $user->kta_number) }}" maxlength="15" pattern="\d*"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            required />
                                    </div>
                                </div>

                                <!-- Input Nomor Barcode -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nomor Barcode</label>
                                        <input class="form-control" type="text" name="barcode_number"
                                            value="{{ old('barcode_number', $user->barcode_number) }}" maxlength="20"
                                            pattern="\d*" inputmode="numeric"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" required />
                                    </div>
                                </div>


                                <!-- Input Departemen -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Departemen</label>
                                        <input class="form-control" type="text" name="department"
                                            value="{{ old('department', $user->department) }}" required />
                                    </div>
                                </div>

                                <!-- Input Phone -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>No. Telepon / WA</label>
                                        <input class="form-control" type="text" name="phone"
                                            value="{{ old('phone', $user->phone) }}" maxlength="15" pattern="\d*"
                                            inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                                    </div>
                                </div>

                                <!-- Input Email -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ old('email', $user->email) }}" />
                                    </div>
                                </div>

                                <!-- Input Jenis Kelamin -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Jenis Kelamin</label>
                                        <select class="form-select" name="gender" required>
                                            <option value="">-- Pilih Jenis Kelamin --</option>
                                            <option value="male"
                                                {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                                Laki-Laki</option>
                                            <option value="female"
                                                {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                                Perempuan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Input Tempat Lahir -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tempat Lahir</label>
                                        <input class="form-control" type="text" name="birth_place"
                                            value="{{ old('birth_place', $user->birth_place) }}" />
                                    </div>
                                </div>

                                <!-- Input Tanggal Lahir -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tanggal Lahir</label>
                                        <input class="form-control" type="date" name="birth_date"
                                            value="{{ old('birth_date', $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}" />
                                    </div>
                                </div>

                                <!-- Input Agama -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Agama</label>
                                        <select class="form-select" name="religion" required>
                                            <option value="">-- Pilih Agama --</option>
                                            @php
                                                $religions = [
                                                    'Islam',
                                                    'Kristen',
                                                    'Katolik',
                                                    'Hindu',
                                                    'Buddha',
                                                    'Konghucu',
                                                    'Lainnya',
                                                ];
                                            @endphp

                                            @foreach ($religions as $item)
                                                <option value="{{ $item }}"
                                                    {{ old('religion', $user->religion) == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <!-- Input Pendidikan -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pendidikan</label>
                                        {{-- <input class="form-control" type="text" name="education"
                                            value="{{ old('education', $user->education) }}" /> --}}
                                         <select class="form-select" name="education" required>
                                            <option value="">-- Pilih Pendidikan --</option>
                                            @php
                                                $educations = [
                                                    'SD',
                                                    'SMP',
                                                    'SMA/SMK',
                                                    'D3',
                                                    'S1',
                                                    'S2',
                                                    'S3',
                                                ];
                                            @endphp
                                            @foreach ($educations as $item)
                                                <option value="{{ $item }}"
                                                    {{ old('education', $user->education) == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Input Alamat -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label>Alamat</label>
                                        <textarea class="form-control" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div>

                                <!-- Input PIN -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>PIN</label>
                                        <input class="form-control" type="text" name="pin"
                                            value="{{ old('pin') }}" maxlength="6" pattern="\d*"
                                            inputmode="numeric"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
                                        <small class="text-muted">
                                            Kosongkan jika tidak ingin mengubah PIN
                                        </small>
                                    </div>
                                </div>

                                <!-- Input Password -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Password</label>
                                        <input class="form-control" type="text" name="password"
                                            value="{{ old('password') }}" />
                                        <small class="text-muted">
                                            Kosongkan jika tidak ingin mengubah password
                                        </small>
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
    @endpush
@endsection
