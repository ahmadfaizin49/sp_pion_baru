@extends('layouts.master')

@section('title')
    Edit Registrasi Member
@endsection

@push('css')
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

                        {{-- Form untuk edit registrasi --}}
                        <form method="POST" action="{{ route('members.update', $member->id) }}" class="form theme-form">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Nama</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $member->name) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>NIK</label>
                                        <input class="form-control" type="text" name="nik"
                                            value="{{ old('nik', $member->nik) }}" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Departemen</label>
                                        <input class="form-control" type="text" name="department"
                                            value="{{ old('department', $member->department) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>No. Telepon / WA</label>
                                        <input class="form-control" type="text" name="phone"
                                            value="{{ old('phone', $member->phone) }}" required />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tempat Lahir</label>
                                        <input class="form-control" type="text" name="birth_place"
                                            value="{{ old('birth_place', $member->birth_place) }}" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Tanggal Lahir</label>
                                        <input class="form-control" type="date" name="birth_date"
                                            value="{{ old('birth_date', $member->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('Y-m-d') : '') }}"
                                            required />
                                    </div>
                                </div>

                                <div class="col-md-6">
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
                                </div>
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
                                                    {{ old('religion', $member->religion) == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Pendidikan</label>
                                        {{-- <input class="form-control" type="text" name="education"
                                            value="{{ old('education', $member->education) }}" /> --}}
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
                                                    {{ old('education', $member->education) == $item ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label>Alamat</label>
                                        <textarea class="form-control" name="address" rows="3" required>{{ old('address', $member->address) }}</textarea>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
