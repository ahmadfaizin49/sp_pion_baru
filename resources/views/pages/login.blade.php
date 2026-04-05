@extends('layouts.auth')

@section('title')
    Login
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/prism.css') }}">
@endpush

@section('content')
    <section>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12">
                    <div class="login-card">
                        <form method="POST" action="{{ route('login.post') }}" class="theme-form login-form">
                            @csrf

                            <h4>Login Admin</h4>
                            <h6>Silakan masuk ke akun Anda.</h6>

                            {{-- Alert Error --}}
                            @if (session('error'))
                                <div class="alert alert-soft-danger alert-dismissible fade show border-0" role="alert">
                                    <i class="fa fa-exclamation-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-soft-danger alert-dismissible fade show border-0" role="alert">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input class="form-control" type="text" name="username" placeholder="Masukkan Username"
                                    required autocomplete="username" />
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input class="form-control" type="password" id="password" name="password"
                                        placeholder="Masukkan Password" required autocomplete="current-password" />
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn-premium btn-premium-lg btn-premium-primary btn-premium-white w-100 border-0 mt-3">
                                    Login
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="{{ asset('assets/js/height-equal.js') }}"></script>
        <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#togglePassword').on('click', function() {
                    const passwordField = $('#password');
                    const icon = $(this).find('i');

                    if (passwordField.attr('type') === 'password') {
                        passwordField.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            });
        </script>
    @endpush
@endsection
