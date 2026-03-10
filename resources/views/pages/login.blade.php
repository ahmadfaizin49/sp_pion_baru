@extends('layouts.auth')

@section('title')
    Login
@endsection

@push('css')
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

                            <h4>Login</h4>
                            <h6>Welcome back! Log in to your account.</h6>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input class="form-control" type="text" name="username" placeholder="Username"
                                    required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" placeholder="Password"
                                    required />
                            </div>

                            {{-- <div class="form-group">
                                <div class="checkbox">
                                    <input id="checkbox1" type="checkbox" name="remember" />
                                    <label for="checkbox1">Remember me</label>
                                </div>
                                <a class="link" href="">Forgot password?</a>
                            </div> --}}

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary w-100">
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
    @endpush
@endsection
